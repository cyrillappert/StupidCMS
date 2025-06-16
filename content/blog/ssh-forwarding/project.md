---
title: "Mobile Pi Access"
published: true
date: 2025-06-16
featured_img:
  type: img
  src: ssh.png
  alt: "Raspberry Pi with an LTE hat powered by a powerbank"
tags:
  - Raspberry
  - Networking
---

## SSH Forwarding for Remote Access

Had a project where a Raspberry Pi needed to be accessible remotely over a mobile connection. No fixed IP, no port forwarding possible on the mobile carrier. SSH forwarding solved this cleanly.

![medium:SSH tunnel setup](ssh.png)

The Pi connects out to a server with a public IP, establishing a reverse tunnel. Then you can access the Pi from anywhere through that server.

### The Basic Setup

The Raspberry Pi runs this command to create the tunnel:

```bash
ssh -R 2222:localhost:22 user@your-server.com
```

This forwards port 2222 on the server back to port 22 (SSH) on the Pi. Now you can SSH to the Pi via:

```bash
ssh -p 2222 pi@your-server.com
```

### SSH Forwarding Options

SSH has three main forwarding modes:

#### -L (Local Port Forwarding)
Forwards a local port to a remote destination:

```bash
ssh -L 8080:localhost:80 user@server.com
```

This forwards your local port 8080 to port 80 on the server. Visit `localhost:8080` to access the remote web service.

#### -R (Remote Port Forwarding)
Forwards a remote port back to your local machine:

```bash
ssh -R 9000:localhost:3000 user@server.com
```

Port 9000 on the server now points to port 3000 on your machine. Useful for exposing local services.

#### -D (Dynamic Port Forwarding)
Creates a SOCKS proxy:

```bash
ssh -D 1080 user@server.com
```

Set your browser to use `localhost:1080` as a SOCKS proxy. All traffic routes through the server.
