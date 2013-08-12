<p align="center">
    <img src="https://raw.github.com/henrikbjorn/Stampie/next-version/doc/logo.png" alt="Stampie" />
</p>

Stampie is a mailer for online services such as Mandrill or Postmark and with support for even more.
It was created to have a lightwieight alternative to Swift.

Stampie is created in 3 parts.

1. Adapters. Adapters provide the funcationality to talk to API endpoints in a structured way.
2. Handlers. Handlers handle formatting of messages and sending the request. There is a Handler
for each supported service.
3. Mailer. The mailer calls the Handler and dispatch events for the lifecycle of sending each
message.


