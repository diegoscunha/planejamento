@component('mail::message')
# Olá, {{ $user->name }}

Seu usuário foi criado com sucesso!<br>
Segue os dados para o acesso.<br>

E-mail: {{ $user->email }}<br>
Senha: {{ $user->senha }}

@component('mail::button', ['url' => $url])
Acessar o sistema
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
