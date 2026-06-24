<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #f9fafb; padding: 24px;">
    <div style="background: #1e293b; padding: 20px 24px; border-radius: 8px 8px 0 0;">
        <h1 style="color: #f8fafc; margin: 0; font-size: 20px;">🎓 STEM Academy</h1>
    </div>
    <div style="background: white; padding: 24px; border-radius: 0 0 8px 8px; border: 1px solid #e2e8f0; border-top: none;">
        <h2 style="color: #1e293b; margin-top: 0;">Student Login Alert</h2>
        <p style="color: #475569;">A student has logged into the portal:</p>
        <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
            <tr>
                <td style="padding: 8px; background: #f1f5f9; font-weight: bold; width: 120px;">Name</td>
                <td style="padding: 8px;">{{ $studentName }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; background: #f1f5f9; font-weight: bold;">Email</td>
                <td style="padding: 8px;">{{ $studentEmail }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; background: #f1f5f9; font-weight: bold;">Time</td>
                <td style="padding: 8px;">{{ $time }}</td>
            </tr>
        </table>
    </div>
    <p style="text-align: center; color: #94a3b8; font-size: 12px; margin-top: 16px;">
        This is an automated message from STEM Academy LMS.
    </p>
</div>
