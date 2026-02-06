# Fire Extinguisher Page - Bug Fixes Plan
## Date: February 5, 2026

## Issues to Fix:

### 1. **Room Addition Bug - Total Rooms Incrementing** ❌
**Problem:** Every time a room is added, the building's `rooms` column increments. This is wrong because `rooms` should represent the **maximum/required** number of rooms, not the current count.

**Root Cause:** Line 378 in `FireSafetyController.php` - `$building->increment('rooms');`

**Solution:** Remove this line. The `rooms` column should only be set when creating/updating the building, not when adding individual room records.

---

### 2. **Floor Selection Logic for Add Room** ❌
**Problem:** Need to implement smart floor selection that prevents assigning rooms to a floor if it would violate the "minimum 1 room per floor" rule.

**Example:** Building with 3 floors & 8 total rooms:
- If Floor 1 has 6 rooms, it should no longer appear in the dropdown
- This ensures Floors 2 and 3 can each have at least 1 room

**Solution:** 
- Add database columns: `maximum_floors` and `maximum_rooms` to track separately
- Implement JavaScript logic to calculate available floors based on current room distribution
- Disable floors that would prevent other floors from having at least 1 room

---

### 3. **Inspect & Update Room Modal Missing** ❌
**Problem:** Clicking "Inspect & Update" shows error "Failed to load room details"

**Root Cause:** The `openUpdateRoomModal` function doesn't exist, and there's no Update Room modal

**Solution:** 
- Create Update Room Modal with fields: Room Name, Room Code, Nearest Room (for extinguisher coverage)
- Implement `openUpdateRoomModal()` function
- Implement backend route and controller method to handle room updates

---

### 4. **Add Extinguisher - Floor Data Not Loading** ❌
**Problem:** Add Extinguisher modal only shows "Floor 1", doesn't load all floors like Add Room does

**Root Cause:** Line 1002 in extinguishers.blade.php - the extBuildingSelect option doesn't include `data-floors` attribute

**Solution:** Add `opt2.dataset.floors = b.floors || 1;` when populating extBuildingSelect

---

### 5. **Extinguisher Type "Other" - Can't Type** ❌
**Problem:** When selecting "Other, Please Specify..." in Add Extinguisher Type, the modal appears but user can't type

**Root Cause:** The SweetAlert2 input modal is working, but the implementation might have issues

**Solution:** Verify and fix the `handleExtTypeChange()` function to ensure the input is editable

---

### 6. **Status "For Purchase" Invalid** ❌
**Problem:** Selecting "For Purchase" in Add/Update Extinguisher shows "selected status is invalid"

**Root Cause:** Backend validation in `storeExtinguisher` and `updateExtinguisher` only allows: `active, expired, maintenance, missing` - doesn't include `purchase`

**Solution:** 
- Update validation rules to include `purchase` and `decommissioned`
- Add database migration to add these statuses to the enum/check constraint

---

### 7. **Add "Decommissioned" Status** ❌
**Problem:** Need to add "Decommissioned" status option

**Requirements:**
- Add to both Add and Update Extinguisher modals
- Decommissioned extinguishers are automatically marked as "Failed" in evaluation
- Only "OK (Active)" status counts as "Passed"

**Solution:**
- Add "Decommissioned" option to status dropdowns
- Update backend validation
- Update evaluation logic to mark decommissioned as failed

---

## Implementation Plan:

1. Fix database schema (add columns, update constraints)
2. Fix backend controller methods
3. Fix frontend modals and JavaScript
4. Test all scenarios

