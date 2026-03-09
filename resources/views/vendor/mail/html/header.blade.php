@props(['url'])
<tr>
<td class="header" style="padding: 25px 0; text-align: center;">
    <a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto;">
            <tr>
                <td style="vertical-align: middle; padding-right: 12px;">
                    <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="DRRM Logo" style="height: 54px; width: auto; display: block;">
                </td>
                <td style="vertical-align: middle; text-align: left;">
                    <div style="font-size: 13px; font-weight: 700; color: #111827; line-height: 1.2;">
                        User verification
                    </div>
                    <div style="font-size: 12px; color: #6b7280; line-height: 1.2;">
                        DepEd — Disaster Risk Reduction and Management (DRRM)
                    </div>
                </td>
                <td style="vertical-align: middle; padding-left: 12px;">
                    <img src="{{ asset('images/What-Is-the-Difference-Between-DepEd-Seal-and-DepEd-Logo.png') }}" alt="DepEd Logo" style="height: 54px; width: auto; display: block;">
                </td>
            </tr>
        </table>
    </a>
</td>
</tr>
