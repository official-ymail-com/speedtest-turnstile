![LibreSpeed Turnstile Logo](https://github.com/librespeed/speedtest/blob/master/.logo/logo3.png?raw=true)

# LibreSpeed Turnstile

Speedtest with Cloudflare Turnstile validation

## Compatibility
All modern browsers are supported: IE11, latest Edge, latest Chrome, latest Firefox, latest Safari.  
Works with mobile versions too.

## Features
* Cloudflare Turnstile validation
* Download
* Upload
* Ping
* Jitter
* IP Address, ISP, distance from server (optional)
* Telemetry (optional)
* Results sharing (optional)
* Multiple Points of Test (optional)


## Server requirements
* A reasonably fast web server with Apache 2 (nginx, IIS also supported)
* PHP 5.4 (other backends also available)
* MySQL database to store test results (optional, PostgreSQL and SQLite also supported)
* A fast! internet connection

## Installation 
* Edit file cf-turnstile/config.php, change TurnStile_Site_Key to your own Cloudflare TurnStile server side "Secret Key"
* Edit file index.html, change line 369, change "sitekey" to your own Cloudflare TurnStile client side "Site Key"

## Go backend

## Node.js backend

## Donate

## License
Copyright (C) 2016-2022 Federico Dossena

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/lgpl>.
