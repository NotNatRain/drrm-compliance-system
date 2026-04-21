from __future__ import annotations

import json
import re
from pathlib import Path
from typing import Any

from pypdf import PdfReader

INPUT_DIR = Path(r"c:/xampp/htdocs/drrmcompliance/encoded_schools_inspection_record")
OUTPUT_PATH = Path(r"c:/xampp/htdocs/drrmcompliance/storage/app/recovery/fire_safety_pdf_recovery.json")
MAX_PAGES_TO_SCAN = 8

DATE_RE = re.compile(r"[A-Za-z]{3}\s+\d{1,2},\s+\d{4}")

ROOM_TYPE_MAP = {
    "classroom and administration": "others",
    "principal's office": "administration",
    "administration": "administration",
    "laboratory": "laboratory",
    "classroom": "classroom",
    "clinic": "clinic",
    "canteen": "others",
    "library": "others",
}

ALARM_STATUS_MAP = {
    "active": "active",
    "functional": "functional",
    "online": "online",
    "broken": "broken",
    "defective": "under_repair",
    "offline": "offline",
    "maintenance": "maintenance",
}

EXT_STATUS_MAP = {
    "active": "active",
    "for preventive maintenance": "for preventive maintenance",
    "maintenance": "maintenance",
    "for purchase": "for purchase",
    "purchase": "purchase",
    "broken": "broken",
    "missing": "missing",
    "expired": "expired",
}


def normalize_spaces(s: str) -> str:
    return re.sub(r"\s+", " ", s).strip()


def split_pages_text(pdf_path: Path) -> list[str]:
    reader = PdfReader(str(pdf_path))
    pages: list[str] = []
    for page in reader.pages[:MAX_PAGES_TO_SCAN]:
        try:
            pages.append(page.extract_text() or "")
        except Exception:
            pages.append("")
    return pages


def extract_school_meta(all_text: str) -> dict[str, str]:
    name = ""
    school_id = ""

    m_name = re.search(r"Name of School:\s*(.*?)\s+Name of School Head:", all_text, re.IGNORECASE)
    if m_name:
        name = normalize_spaces(m_name.group(1))

    m_id = re.search(r"School ID:\s*(\d+)", all_text, re.IGNORECASE)
    if m_id:
        school_id = m_id.group(1)

    return {"school_name": name, "school_id": school_id}


def parse_buildings(first_page_text: str) -> list[dict[str, Any]]:
    rows: list[dict[str, Any]] = []
    lines = [normalize_spaces(x) for x in first_page_text.splitlines() if normalize_spaces(x)]

    in_table = False
    for line in lines:
        upper = line.upper()
        if "SCHOOL'S SUMMARIZATION OF FIRE SAFETY" in upper:
            break

        if "BUILDING" in upper and "NUMBER" in upper:
            in_table = True
            continue

        if not in_table:
            continue

        m = re.match(r"^(ANC\s*\d+|\d{1,3})\b", line, re.IGNORECASE)
        if not m:
            continue

        code = m.group(1).upper().replace(" ", "")
        rest = line[m.end():].strip()

        # building name is text before first obvious numeric floor token.
        m_floor = re.search(r"\b([1-4])\b", rest)
        floors = int(m_floor.group(1)) if m_floor else 1
        building_name = None
        if m_floor:
            lead = rest[: m_floor.start()].strip(" -")
            if lead and not re.fullmatch(r"\d+", lead):
                building_name = lead

        rows.append(
            {
                "building_no": code,
                "building_name": building_name,
                "floors": floors,
                "rooms": 1,
                "raw": line,
            }
        )

    # Deduplicate by building code
    out: dict[str, dict[str, Any]] = {}
    for b in rows:
        out[b["building_no"]] = b
    return list(out.values())


def section_text(all_text: str, start_hint: str, stop_hints: list[str]) -> str:
    src = all_text
    i = src.upper().find(start_hint.upper())
    if i < 0:
        return ""

    sliced = src[i:]
    stop_idx = len(sliced)
    upper = sliced.upper()
    for stop in stop_hints:
        j = upper.find(stop.upper())
        if j >= 0:
            stop_idx = min(stop_idx, j)
    return sliced[:stop_idx]


def flat_section(all_text: str, start_hint: str, stop_hints: list[str]) -> str:
    flat = normalize_spaces(all_text)
    upper = flat.upper()
    start = upper.find(start_hint.upper())
    if start < 0:
        return ""

    tail = flat[start:]
    tail_upper = tail.upper()
    stop_idx = len(tail)
    for stop in stop_hints:
        i = tail_upper.find(stop.upper())
        if i >= 0:
            stop_idx = min(stop_idx, i)

    return tail[:stop_idx]


def parse_alarms(all_text: str) -> list[dict[str, Any]]:
    sec = flat_section(
        all_text,
        "ALARM CODE",
        ["EXTINGUISHER CODE", "ROOM INFORMATION"],
    )
    if not sec:
        return []

    sec = re.sub(r"\s+", " ", sec)
    rows = re.findall(r"ALARM[\s-]?\d+.*?(?=\s+ALARM[\s-]?\d+|$)", sec, flags=re.IGNORECASE)

    out: list[dict[str, Any]] = []
    for row in rows:
        m_code = re.match(r"^(ALARM[\s-]?\d+)\b", row, re.IGNORECASE)
        if not m_code:
            continue

        code = normalize_spaces(m_code.group(1)).upper().replace(" ", "")
        rest = row[m_code.end():].strip()

        m_type = re.search(r"\b(Mechanical|Bell|Digital)\b", rest, re.IGNORECASE)
        if not m_type:
            continue

        m_status = re.search(r"\b(ACTIVE|FUNCTIONAL|ONLINE|BROKEN|DEFECTIVE|OFFLINE|MAINTENANCE)\b", rest, re.IGNORECASE)
        if not m_status:
            continue

        dates = [m.group(0) for m in DATE_RE.finditer(rest)]
        if len(dates) < 2:
            continue

        location = normalize_spaces(rest[: m_type.start()])
        alarm_type = m_type.group(1)
        status_raw = m_status.group(1).lower()
        last_test = dates[0]
        next_due = dates[1]

        m_next_due = re.search(re.escape(next_due), rest, re.IGNORECASE)
        remarks = normalize_spaces(rest[m_next_due.end():]) if m_next_due else ""
        if "Overall Purpose:" in remarks:
            remarks = normalize_spaces(remarks.split("Overall Purpose:", 1)[0])

        out.append(
            {
                "code": code,
                "location": location,
                "alarm_type": alarm_type,
                "status": ALARM_STATUS_MAP.get(status_raw, "maintenance"),
                "status_raw": status_raw,
                "last_test": last_test,
                "next_test_due": next_due,
                "remarks": remarks,
                "raw": row,
            }
        )

    dedup: dict[str, dict[str, Any]] = {}
    for a in out:
        dedup[a["code"]] = a
    return list(dedup.values())


def parse_extinguishers(all_text: str) -> list[dict[str, Any]]:
    sec = flat_section(
        all_text,
        "EXTINGUISHER CODE",
        ["ROOM INFORMATION", "DEPED DRRM EVACUATION PLANS REPORT"],
    )
    if not sec:
        return []

    sec = normalize_spaces(sec)
    if "REMARKS" in sec:
        sec = sec.split("REMARKS", 1)[1].strip()
    sec = re.sub(r"(MAINTENANCE|PURCHASE|ACTIVE|EXPIRED|MISSING|BROKEN)([A-Za-z]{3}\s+\d{1,2},\s+\d{4})", r"\1 \2", sec)
    rows = re.findall(r"FE\s*-?\s*\d+.*?(?=\s+FE\s*-?\s*\d+\s+|\s+Overall Purpose:|$)", sec, flags=re.IGNORECASE)

    m_leading = re.search(r"^(\d{2}\s+.*?)(?=\s+FE\s*-?\s*\d+\s+|$)", sec, flags=re.IGNORECASE)
    if m_leading and DATE_RE.search(m_leading.group(1)):
        rows.insert(0, m_leading.group(1))

    out: list[dict[str, Any]] = []
    status_tokens = [
        "FOR PREVENTIVE MAINTENANCE",
        "FOR PURCHASE",
        "MAINTENANCE",
        "PURCHASE",
        "ACTIVE",
        "BROKEN",
        "MISSING",
        "EXPIRED",
    ]

    for row in rows:

        m_code = re.match(r"^(FE\s*-?\s*\d+|\d{2})\b", row, re.IGNORECASE)
        if not m_code:
            continue

        code_raw = normalize_spaces(m_code.group(1)).upper().replace(" ", "")
        if not code_raw.startswith("FE"):
            code = f"FE{code_raw.zfill(2)}"
        else:
            code = code_raw.replace("-", "")

        rest = row[m_code.end():].strip()
        m_date = DATE_RE.search(rest)
        if not m_date:
            continue

        m_type = re.search(r"\b(ABC|CO2|HCFC|FPM|FP|FR)\b", rest[m_date.end():], re.IGNORECASE)
        if not m_type:
            continue

        status_match = None
        for token in status_tokens:
            m_status = re.search(rf"\b{re.escape(token)}\b", rest, re.IGNORECASE)
            if m_status and m_status.start() < m_date.start():
                if status_match is None or m_status.start() > status_match.start():
                    status_match = m_status

        if not status_match:
            continue

        location = normalize_spaces(rest[: status_match.start()])
        status_raw = status_match.group(0).lower()
        checked = m_date.group(0)
        ext_type = m_type.group(1).upper()
        type_end_abs = m_date.end() + m_type.end()
        remarks = normalize_spaces(rest[type_end_abs:])
        for marker in ("Overall Purpose:", "Summary Existing / Needed", "Status Totals"):
            if marker in remarks:
                remarks = normalize_spaces(remarks.split(marker, 1)[0])
        if "FIRE EXTINGUISHER INSPECTION AND COVERAGE DETAILS" in remarks:
            remarks = normalize_spaces(remarks.split("FIRE EXTINGUISHER INSPECTION AND COVERAGE DETAILS", 1)[0])

        out.append(
            {
                "code": code,
                "location": location,
                "status": EXT_STATUS_MAP.get(status_raw, "maintenance"),
                "status_raw": status_raw,
                "type": ext_type,
                "date_checked": checked,
                "remarks": remarks,
                "raw": row,
            }
        )

    dedup: dict[str, dict[str, Any]] = {}
    for e in out:
        dedup[e["code"]] = e
    return list(dedup.values())


def parse_rooms(all_text: str) -> list[dict[str, Any]]:
    sec = section_text(
        all_text,
        "ROOM INFORMATION",
        ["DEPED DRRM EVACUATION PLANS REPORT", "Prepared by:"],
    )
    if not sec:
        return []

    lines = [normalize_spaces(x) for x in sec.splitlines() if normalize_spaces(x)]
    rows: list[str] = []
    current = ""
    for line in lines:
        if re.match(r"^\d+\b", line):
            if current:
                rows.append(current)
            current = line
        else:
            if current:
                current += " " + line
    if current:
        rows.append(current)

    parsed: list[dict[str, Any]] = []
    room_type_keys = sorted(ROOM_TYPE_MAP.keys(), key=len, reverse=True)

    for row in rows:
        m_idx = re.match(r"^(\d+)\s+(.*)$", row)
        if not m_idx:
            continue
        body = m_idx.group(2)

        parts = body.split(" ", 1)
        if len(parts) < 2:
            continue

        room_code = parts[0]
        tail = parts[1]

        m = re.search(r"\b(ANC\s*\d+|\d{1,3})(?:\s*-\s*[A-Z0-9' ]+)?\s+([12-])\s+", tail, re.IGNORECASE)
        if not m:
            continue

        building_no = m.group(1).upper().replace(" ", "")
        floor_raw = m.group(2)
        floor_no = int(floor_raw) if floor_raw.isdigit() else 1
        room_name = normalize_spaces(tail[: m.start()])
        after = tail[m.end():]

        room_type_src = "others"
        for k in room_type_keys:
            if after.lower().startswith(k):
                room_type_src = k
                after = after[len(k):].strip()
                break

        yn = re.findall(r"\b(Yes|No|N/A)\b", after, flags=re.IGNORECASE)
        sec_exit = yn[0].lower() if len(yn) >= 1 else "n/a"
        smoke = yn[1].lower() if len(yn) >= 2 else "n/a"

        has_secondary_exit = None if sec_exit == "n/a" else (sec_exit == "yes")
        has_smoke_detector = None if smoke == "n/a" else (smoke == "yes")

        parsed.append(
            {
                "room_code": room_code,
                "room_name": room_name or room_code,
                "building_no": building_no,
                "floor_no": floor_no,
                "room_type": ROOM_TYPE_MAP.get(room_type_src, "others"),
                "has_secondary_exit": has_secondary_exit,
                "has_smoke_detector": has_smoke_detector,
                "raw": row,
            }
        )

    dedup: dict[tuple[str, str, str], dict[str, Any]] = {}
    for r in parsed:
        key = (r["building_no"], r["room_code"], r["room_name"])
        dedup[key] = r
    return list(dedup.values())


def reconcile_building_stats(buildings: list[dict[str, Any]], rooms: list[dict[str, Any]]) -> list[dict[str, Any]]:
    by_code: dict[str, dict[str, Any]] = {b["building_no"]: b for b in buildings}

    room_groups: dict[str, list[dict[str, Any]]] = {}
    for r in rooms:
        room_groups.setdefault(r["building_no"], []).append(r)

    for code, rs in room_groups.items():
        floors = max((int(x.get("floor_no") or 1) for x in rs), default=1)
        uniq_rooms = {(x.get("room_code"), x.get("room_name")) for x in rs}
        room_count = max(1, len(uniq_rooms))

        if code not in by_code:
            by_code[code] = {
                "building_no": code,
                "building_name": None,
                "floors": floors,
                "rooms": room_count,
                "raw": "derived-from-rooms",
            }
        else:
            by_code[code]["floors"] = max(int(by_code[code].get("floors") or 1), floors)
            by_code[code]["rooms"] = max(int(by_code[code].get("rooms") or 1), room_count)

    return list(by_code.values())


def recover_from_pdf(pdf_path: Path) -> dict[str, Any]:
    pages = split_pages_text(pdf_path)
    all_text = "\n".join(pages)

    school = extract_school_meta(all_text)
    rooms = parse_rooms(all_text)
    buildings = parse_buildings(pages[0] if pages else "")
    buildings = reconcile_building_stats(buildings, rooms)
    alarms = parse_alarms(all_text)
    extinguishers = parse_extinguishers(all_text)

    return {
        "source_file": pdf_path.name,
        "school": school,
        "buildings": buildings,
        "alarms": alarms,
        "extinguishers": extinguishers,
        "rooms": rooms,
    }


def main() -> None:
    OUTPUT_PATH.parent.mkdir(parents=True, exist_ok=True)

    results = [recover_from_pdf(pdf) for pdf in sorted(INPUT_DIR.glob("*.pdf"))]
    payload = {
        "generated_from": str(INPUT_DIR),
        "reports": results,
    }

    OUTPUT_PATH.write_text(json.dumps(payload, indent=2), encoding="utf-8")
    print(f"Saved recovery payload: {OUTPUT_PATH}")

    for report in results:
        s = report["school"]
        print(
            f"- {s.get('school_name')} ({s.get('school_id')}): "
            f"buildings={len(report['buildings'])}, alarms={len(report['alarms'])}, "
            f"extinguishers={len(report['extinguishers'])}, rooms={len(report['rooms'])}"
        )


if __name__ == "__main__":
    main()
