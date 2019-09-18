# Tatter\Agents
Service analysis and assessment for CodeIgniter 4

## Quick Start

1. Install with Composer: `> composer require tatter/agents`
2. Register agent handlers: `> php spark handlers:register`
3. Check all agent statuses: `> php spark agents:check`

## Features

The Agents library defines a number of individual Agents that gather status information
from the server, framework, and various services and modules into a streamlined data store.

## Installation

Install easily via Composer to take advantage of CodeIgniter 4's autoloading capabilities
and always be up-to-date:
* `> composer require tatter/agents`

Or, install manually by downloading the source files and adding the directory to
`app/Config/Autoload.php`.

## Configuration (optional)

The library's default behavior can be altered by extending its config file. Copy
**bin/Agents.php** to **app/Config/** and follow the instructions
in the comments. If no config file is found in **app/Config** the library will use its own.

## Usage

After installing and registering the handlers (`> php spark handlers:register`) agents can
be loaded individually from their model & entity, or run centrally with the provided CLI
command: `php spark agents:update`. Most likely you will want to create a cron job to run
this at periodic intervals.

Agent results are stored in the `agents_results` table with its corresponding model
`Tatter\Agents\Models\ResultModel`. Large data objects are serialized and hashed into a
separated table that checks for duplicate data to cut down on storage sizes.
