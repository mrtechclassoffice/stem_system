<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #f9fafb; padding: 24px;">
    <div style="background: #1e293b; padding: 20px 24px; border-radius: 8px 8px 0 0;">
        <h1 style="color: #f8fafc; margin: 0; font-size: 20px;">🎓 STEM Academy</h1>
    </div>
    <div style="background: white; padding: 24px; border-radius: 0 0 8px 8px; border: 1px solid #e2e8f0; border-top: none;">
        <h2 style="color: #1e293b; margin-top: 0;">Welcome, {{ $studentName }}!</h2>
        <p style="color: #475569;">Your account has been created on the STEM Academy portal. Here are your login details:</p>
        <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
            <tr>
                <td style="padding: 8px; background: #f1f5f9; font-weight: bold; width: 120px;">Email</td>
                <td style="padding: 8px;">{{ $email }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; background: #f1f5f9; font-weight: bold;">Password</td>
                <td style="padding: 8px; font-family: monospace;">{{ $password }}</td>
            </tr>
        </table>
        <p style="color: #ef4444; font-size: 13px;">⚠️ Please keep your credentials private and do not share them with anyone.</p>
        <a href="{{ route('login') }}" style="display: inline-block; background: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; margin-top: 8px; font-weight: bold;">Log In Now →</a>
    </div>
    <p style="text-align: center; color: #94a3b8; font-size: 12px; margin-top: 16px;">
        This is an automated message from STEM Academy LMS.
    </p>
</div>
