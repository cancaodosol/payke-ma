{{ $user->name }}さん<br />
<br />
PaykeMAに管理ユーザーに新規ユーザー登録しました。<br />
<br />
- メールアドレス： {{ $user->email }}<br />
- パスワード： {{ $password }}<br />
<br />
▼ こちらからログインしてください。<br />
{{ route('login') }}<br />
<br />

