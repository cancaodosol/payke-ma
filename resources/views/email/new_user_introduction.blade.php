@component('mail::message')

{{ $toUser->name }}さん、こんにちは！

# 新しくユーザーが追加されました。

- 名前： {{ $newUser->name }}
- メールアドレス： {{ $newUser->email }}

@endcomponent