@include('emails.shared.header')

<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">Recover your account password</div>
<br>

<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
    Hi, here is your new account password:<br><br>
    <b>Email:</b> {{ $user->email }}<br>
    <b>Password:</b> {{ $new_password }}<br><br>
    <table bgcolor="#c30333" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="45" style="text-align: center; font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: bold; padding: 0 30px 0 30px;">
                <a href="{{ config('app.frontend_url') }}" style="color: #ffffff; text-decoration: none;">
                    Login
                </a>
            </td>
        </tr>
    </table>
  <br><br>
</div>

@include('emails.shared.footer')