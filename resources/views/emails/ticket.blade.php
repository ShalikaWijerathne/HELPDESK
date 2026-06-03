<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: radial-gradient(at 50% -20%, #004a43, #0a1832); padding: 24px 30px; }
        .header h1 { color: #fff; margin: 0; font-size: 20px; letter-spacing: 1px; }
        .body { padding: 30px; color: #333; line-height: 1.6; }
        .ticket-ref { display: inline-block; background: #0a1832; color: #fff; padding: 4px 12px; border-radius: 4px; font-weight: bold; font-size: 14px; margin-bottom: 16px; }
        .message { background: #f8f8f8; border-left: 4px solid #004a43; padding: 12px 16px; margin: 16px 0; border-radius: 0 4px 4px 0; }
        .detail-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .detail-table td { padding: 8px 0; border-bottom: 1px solid #eee; vertical-align: top; }
        .detail-label { color: #888; width: 110px; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 24px; background: #004a43; color: #fff !important; text-decoration: none; border-radius: 4px; font-weight: bold; }
        .footer { background: #f4f4f4; padding: 16px 30px; font-size: 12px; color: #999; text-align: center; }
    </style>
</head>
<body>
<div class="container">

    <div class="header">
        <h1>IT HELP DESK</h1>
    </div>

    <div class="body">
        <span class="ticket-ref">{{ $ticket->reference }}</span>

        <div class="message">{{ $emailMessage }}</div>

        <table class="detail-table">
            <tr>
                <td class="detail-label">Subject</td>
                <td>{{ $ticket->subject }}</td>
            </tr>
            <tr>
                <td class="detail-label">Category</td>
                <td>{{ $ticket->category->name }}</td>
            </tr>
            <tr>
                <td class="detail-label">Priority</td>
                <td>{{ $ticket->priority->name }}</td>
            </tr>
            <tr>
                <td class="detail-label">Status</td>
                <td>{{ $ticket->statusLabel() }}</td>
            </tr>
            <tr>
                <td class="detail-label">Raised by</td>
                <td>{{ $ticket->requester->name }}</td>
            </tr>
        </table>

        <a href="{{ config('app.url') }}/tickets/{{ $ticket->id }}" class="btn">View Ticket</a>
    </div>

    <div class="footer">
        IT Help Desk Ticketing System &mdash; University of Colombo School of Computing<br>
        This is an automated notification. Please do not reply to this email.
    </div>

</div>
</body>
</html>
