## Architektūra

**Diagrama**
diagrama.php

**Esybės**
- users
| ID | FirstName | Lastname | Password | IsSupport |
|---|---|---|---|---|
| INT (PK) | VARCHAR | VARCHAR | VARCHAR | TINYINT |
- tickets
| ID | SubmitterID | SupporterID | Title | Priority | Status | Description | SubmissionTime |
|---|---|---|---|---|---|---|---|
| INT (PK) | INT | INT | VARCHAR | VARCHAR | VARCHAR | VARCHAR | TIMESTAMP |
- comments
| ID | TicketID | CommentorID | Comment | CommentTime |
|---|---|---|---|---|
| INT (PK) | INT (FK) | INT (FK) | VARCHAR | TIMESTAMP |

**Ryšiai taip esybių**
tickets (ID) --> comments (TicketID)
users (ID) --> comments (CommentorID)