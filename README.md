Shelter
====================

Shelter is a command-line interface for working with Storm On Demand.

Shelter aims at allowing you to perform almost all (if not all) the operations that you can perform in the Storm Management panel.

Shelter is currently under heavy development, so many things may be broken and will most likely change.

Requirements
-------------------
- PHP >= 5.3
- Curl

Usage
-------------------

Update shelter.php with your API Username and Password. Also include a base domain which will be use for bulk creates.

```bash
php shelter.php
```

Currently Supported
--------------------
- Single Creates
- Bulk Creates
- Resizes
- Reboots
- Destroy

Coming Soon / Planned
--------------------
- Backup Create / Restores
- Image Creation / Create From Image
- Re-image
- Clones
- Attach/Detach Private Network
- Add/Remove Public IPs
- Firewall Management
- Load Balancer Support

License
--------------------
See LICENSE.txt for license details.