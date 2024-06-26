{{ $user->name }}さん<br />
<br />
PaykeMAに新規ユーザー登録しました。<br />
<br />
- メールアドレス： {{ $user->email }}<br />
- パスワード： {{ $password }}<br />
<br />
▼ こちらからログインしてください。<br />
<a href="{{ route('login') }}">{{ route('login') }}</a><br />
<br />

