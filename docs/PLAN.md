# Club Treasury Tracker

## Start a new coding chat

Paste the paragraph below into a new chat and attach this plan. Choose a new project folder for the app. This file lives with my club notes for reference.

> I’m the treasurer of Taylor’s Agents of Tech club. Help me build the PHP treasury tracker in this plan, starting with setup in milestone 0 and the manual-entry demo in milestone 1. Work in the project folder selected for this chat. Check the installed PHP, Composer, Laravel and database tooling and install anything missing with Homebrew, then build and test one complete event workflow. Use fictional data in the repository. I’m learning PHP and SQL for a JurisTech software internship, so walk me through one request from the form to the database and leave me one small change I can implement myself. Use the later milestones to guide design. PDF automation and future committee handover can wait until the core ledger works.

## Working across two AI assistants

I'll alternate between Claude Opus 5.5 and Sol 6.2 on this project. They may write code in different styles, so the repository, not either assistant, defines the style. Every session follows these rules.

- **One conventions file.** Milestone 0 creates `AGENTS.md` in the repository root with the rules below. If an assistant reads a different instructions file (for example `CLAUDE.md`), that file only points to `AGENTS.md`, so there is one source of truth.
- **Tools decide formatting.** Format PHP with Laravel Pint using the `laravel` preset, and check types with Larastan. Choose Pest or PHPUnit once in milestone 0, record the choice, and use only that one. Run Pint, Larastan and the tests before every commit.
- **Fixed code patterns:**
  - Every amount goes through the one money helper and is stored in a column ending in `_sen`.
  - Validation lives in Form Request classes, and controllers stay thin.
  - Anything that writes money (income, transfers, expenses, repayments, corrections) is a single action class in `app/Actions`, wrapped in a database transaction.
  - Tables are plural snake_case and models are singular, following Laravel defaults.
  - Comments explain why, not what.
  - Pages are laid out mobile-first with Tailwind, so every screen works on a phone-sized display. This keeps the future mobile access stage cheap.
- **Match the existing code.** Before writing a new class, read a similar existing one and follow it. Don't reformat, rename or restructure code that the task doesn't need to change. Propose a style change as its own task instead of mixing it into a feature.
- **Hand over every session.** End each session with all tests passing, a commit, and a short entry in `docs/progress.md` covering what changed, what's next, and any open decisions. The `docs/progress.md` entry notes which assistant helped. Commits carry only my authorship, with no AI co-author lines, so AI tools never appear as contributors on GitHub.
- **Start every session the same way.** Read `AGENTS.md`, the latest `docs/progress.md` entry and this plan, then run the tests before changing anything.

## What I need as treasurer

I receive school funding and vendor payments from fundraisers. Events also bring in registration fees. Sometimes the club advances money to an organizer. Sometimes a member or I pay for a team meal personally and the club owes a reimbursement. Some outings have no formal proposal. After an event, the committee prepares a financial report in Google Docs and can export it as a PDF.

When I open the app, I want to answer five questions:

1. How much is in each club account as of a given date?
2. Which approved reimbursements remain unpaid, to whom, and for which expenses?
3. How did actual spending compare with the proposal? How much event money is still with the organizer?
4. Which entries in Finance's Statement of Account and in the post-event report already appear in my records, and which need attention?
5. What VAult paperwork is due this week, and who needs to act next?

Primary user for the first release: one treasurer working locally in Malaysian ringgit. Proposed stack: **PHP, Laravel, Blade and MySQL**, with migrations and automated feature tests. Install PHP, Composer, MySQL and poppler with Homebrew. Run the tests against a separate MySQL test database rather than Laravel's default SQLite, so constraints and row locking behave as they do in the app. Keep the database rules accessible enough for me to explain in an interview. Extract PDF text with `pdftotext -layout` (via `spatie/pdf-to-text`), which keeps the debit and credit columns aligned. A Python extraction utility is an option later if multiple document layouts make it worthwhile.

## Taylor's VAult process

From November 2026, Taylor's holds student organisation (SO) funds through **VAult**, the SO Fund Management Framework. Each SO has a designated Virtual Account (VA) that Taylor's Finance administers. The tracker stays internal to the club; it works alongside VAult through file exports and imports, never by connecting to Taylor's systems. Source: the VAult workshop slides (November 2026, Group 2).

| VAult concept | Rule | In the tracker |
|---|---|---|
| Designated Virtual Account | All collections are deposited using the VA number; no other accounts are allowed. Finance holds the money. | The main club account. Its confirmed balance comes from Finance's Statement of Account (SoA). |
| SO Operational Fund | Up to 20% of the total SO balance, capped at RM2,000, kept in the treasurer's personal bank account for routine expenses | A separate account with a cap check |
| Petty cash | One-off request, at most RM2,000 or the fund balance, whichever is lower. Advisor approves. Statement and receipts uploaded by the 25th of each month, then replenished. | A float account that is replenished |
| Cash advance | Event-based, at most RM5,000 or the fund balance, whichever is lower. Advisor approves; large events may get several. Statement within 1 month of disbursement. Unspent money is banked back into the VA; Finance reimburses overspending within 10 working days, subject to VA funds. | The event float, with the advance, the return and any top-up |
| Payment to vendor | Finance pays the vendor directly, within the approved event budget and with no fixed cap. President approves, and the Advisor is consulted for advance payments. Finance pays within 10 working days of a complete request. | An expense paid directly from the VA, with no claim |
| Internal Request Form (IRF) | Required for every disbursement. Treasurer sends it to the Advisor by Monday 5 pm; the Advisor sends it to SO Finance by Thursday 6 pm; Finance pays into the treasurer's account on Wednesdays. | A disbursement request with a status history |
| Collection Listing and SoA | Finance publishes the SoA every Monday, Wednesday and Friday, covering transactions up to the previous day. The treasurer reconciles it against the Collection Listing and resolves discrepancies within 5 working days. | SoA import and reconciliation |
| Shared folder | One OneDrive folder per SO: `0. Templates`, `1. Financial Statement`, `2. Policies & Guidelines`, `3. Disbursements`, `4. Inflow` | A checklist of required documents and suggested file names |

SoA format notes, from the sample:
- Columns: document date, cost element, description, reference document number, name and value in MYR.
- Income appears as negative values and expenses as positive values.
- Bank charges appear as their own small lines, and subtotal rows have no date.
- One reference document number can cover more than one line, so a line is identified by its reference number together with its date, name and amount.

## Money rules

| Real-world action | Expected behavior |
|---|---|
| School approves RM500 for an event | Track expected funding and link the proposal. Increase the account balance on the date the payment actually arrives. |
| Vendor pays a fundraiser fee or students pay registration fees | Record external income once, with the event, recipient account or cash holder, date and reference. An outstanding pledge stays visible separately. |
| Club gives an organizer RM300 | Track a transfer from the club account into the organizer’s event funds. The bank balance falls, the organizer holds RM300, and total club-controlled funds are unchanged. |
| Organizer buys food using event funds | Record an event expense and reduce the organizer’s event funds. Link the receipt. |
| I pay for a team meal personally | Record the expense once and who paid. Track the claim through submission, approval and repayment. The club bank balance changes on repayment. Permit a club-wide meal without an event or proposal. |
| Club repays an approved claim in two installments | Link both payments to the same claim. Show the remaining amount due after each payment. Prevent repayment above the approved amount. |
| Organizer returns unused cash | Transfer the confirmed amount back to the club account. Keep the expected return and any outstanding difference visible until the transfer is confirmed. |

Use exact currency amounts, such as integer sen. Parse typed amounts such as "84.40" into 8440 as text, never through a float, and use one shared helper for parsing and formatting RM. Show bank balance, cash held for an event, event spending, and approved claims due as separate figures. A proposal, an invoice, a report row and a payment confirmation can all describe the same purchase. Linking them must preserve a single expense. Record opening balances with an effective date.

### Ledger invariants

These rules hold everywhere in the app. Each one has an automated test.

1. **Spending and cash movement are separate records.** Expenses record what the club spent; event actuals and category totals read only from expenses. Movements record where money moved; balances read only from movements. A claim repayment is a movement linked to a claim and is never an expense. A directly paid expense is one expense plus one linked movement. This is what stops a reimbursement from counting the expense twice.
2. **Posted movements are never edited or deleted.** A correction is a reversing movement plus a replacement movement, both linked to the original with a reason.
3. **Claims store the claimed amount and the approved amount separately.** The club bears the approved amount, and any difference stays visible as absorbed by the member. Exceeding an event budget is flagged for investigation, not blocked. Budget changes are recorded as dated adjustments with a reason.
4. **Repayments can never exceed the approved amount.** A repayment is saved inside a database transaction that locks the claim row and re-checks approved minus repaid. A database CHECK constraint backs up the rule.
5. **VAult limits are checked before a request leaves the club.** The Operational Fund cap, the petty cash and cash advance limits, and the need for an approved event budget are checked when a request is drafted. A request that breaks a rule is flagged before it goes to the Advisor.

## Suggested records and screens

- **People.** Members and organizers, referenced as payers, claimants and cash holders.
- **Term and event.** Keep the committee term (a date range), event name/date, optional proposal reference and approved budget. Record each budget change as a dated adjustment with a reason, so the event page shows the original budget, adjustments, revised budget and variance. An event can span multiple payments and claims. Retain the original term when handing over records. Balances as of any date come from summing movements up to that date; closing a term saves a snapshot.
- **Account and ledger.** Store the account or cash holder, transaction date, amount, category, counterparty, event if applicable, and optional reference. Cash held by an organizer is an event float account scoped to one event and one person, so "money still with the organizer" is a single balance. Flag a negative float (the organizer overspent) and allow converting it into a claim. Represent a transfer with linked source and destination movements saved together in one database transaction. Calculate balances from posted movements. Corrections follow the reversal rule in the ledger invariants.
- **Expected receipts.** Pledged vendor payments and approved school funding, linked to the movement when the money arrives.
- **Expense and claim.** Store the expense amount, category, payer and evidence. A personally paid expense can have a claim with claimed amount, approved amount and linked repayments. A directly paid expense can link directly to its payment. Capture the small set of categories actually used by the club.
- **VAult accounts.** The club's VA, the Operational Fund in the treasurer's personal account, petty cash and cash advance floats. Record the one-time transfer of club funds into the VA, with its approved Financial Statement, as a transfer.
- **Disbursement requests.** Type (petty cash, cash advance or payment to vendor), amount, event, approver, and a status history: drafted, sent to Advisor, approved, submitted to Finance, paid, statement submitted, closed. Each status records who acts next and when it is due.
- **SoA imports and collection listing.** Keep each imported SoA file privately, one row per SoA line, the match to a ledger movement, and the variance. Collections record date, payer, remitter name, amount, type and notes, matching the school's Collection Listing columns.
- **Privacy.** Store receipts and uploaded PDFs on Laravel's private disk. Add the folder for real files to `.gitignore` from the first commit. Add a login before any hosting, because member names and amounts are personal data. Never store NRIC/passport numbers or personal bank account numbers. The school's statement templates ask for them for reimbursement, so exported forms leave those cells blank for the person to fill in by hand.
- **Report and import rows.** Retain the source document privately. For each extracted row, keep its original date, wording, column, amount, page and review decision, plus a link to the ledger entry or claim where relevant.
- **First screens.** An account ledger with running balance, an event page with budget and actuals, and a reimbursement queue showing who is owed what. Then add SoA reconciliation, a deadlines dashboard, and the report review screen.
- **VAult paperwork.** Export the IRF, Petty Cash Statement, Cash Advance Statement, Collection Listing and Financial Statement as Excel files in the school's layouts, filled from the ledger. For each transaction, show which supporting documents the shared folder needs (proof of payment, quotation, delivery order or invoice, approved IRF) and a suggested file name.
- **Deadlines dashboard.** Upcoming items with their due dates: the weekly IRF cycle, Wednesday payouts, petty cash statements by the 25th, cash advance statements within a month, SoA discrepancies within 5 working days, and vendor payments within 10 working days. Public holidays move these dates, so computed dates are a guide.

## Google Docs and PDF workflow

Keep the committee’s Google Doc template. This importer comes after SoA reconciliation (stage 2), which covers money passing through the VA; the post-event report adds the detail of event spending. The first importer takes an uploaded PDF export. The example is a six-page document with selectable transaction text on page 1, totals on page 2, receipt/payment images on pages 3–5, and sign-off on page 6. The original private example is in Downloads as `Oct'26 Welcoming Party Post-Event Financial Report.pdf`. If the new chat needs to inspect it and cannot access the file, attach it there. Use a fictional equivalent with invented names and no real receipts or signatures for development fixtures, demos and GitHub.

1. Select an event, upload the PDF, and extract draft rows locally. Start with this template’s date, details, debit and credit columns. Handle wrapped descriptions and a totals section on the following page.
2. Preview the original alongside the rows. Separate itemized transactions from totals, balance labels and annotations. Classify each row by its meaning. In this template, the debit column includes the advance and registration fees, while purchases appear under credit.
3. Suggest matches using event, date, amount and description. Let the treasurer choose **match existing**, **create**, **skip summary/duplicate**, or **hold for clarification**. For a new expense, confirm who paid and from which funds. If the report leaves that unclear, keep the row in review.
4. Recalculate totals, show discrepancies and post only confirmed movements. Preserve row decisions across retries. An identical upload should return the previous import. A revised export should be compared with prior posted rows before creating anything new.
5. Keep receipt images available for visual comparison. The first importer can flag arithmetic conflicts in the text automatically. Comparing amounts inside receipt screenshots is a manual step in this version.

### Worked example from the submitted report

| Report evidence | Amount | Interpretation to verify |
|---|---:|---|
| Budget given by Treasurer | RM300.00 | Match the club advance if it was already recorded. |
| Registration fees | RM160.00 | External event income. Confirm where the money was received. |
| Itemized spending | RM343.46 | Sum of the purchase rows as written. Confirm payer and settlement separately. |
| Calculated remainder | RM116.54 | RM300.00 + RM160.00 − RM343.46. The amount returned to the club needs its own confirmation. |

The summary calls RM116.54 “Total Expenditure.” Surface the mismatch with the itemized RM343.46. The Pizza (2) row shows RM84.40 while a payment screenshot shows RM82.40. Leave the RM2.00 difference open for the treasurer to resolve against the originals. Preserve the amounts as reported if a correction is later posted.

## Milestones and acceptance tests

Estimates are cumulative planning ranges for someone learning Laravel, assuming about 8–10 hours per week. Revisit the import estimate after seeing a second and third report.

Each stage ends with an automated feature test, run against the MySQL test database, and leaves one small change for me to implement myself. The "Q" numbers refer to the five questions at the top of this plan.

| Milestone | Cumulative effort | Completion check |
|---|---:|---|
| 0. Setup | 2–3 hours | Install the stack with Homebrew, create the Laravel app, the app and test databases, and a git repository with private files ignored. Add `AGENTS.md`, Pint, Larastan and `docs/progress.md`. `php artisan test` passes on MySQL. |
| 1. Manual internship demo | 8–13 hours | With fictional data, start with RM1,000 in the bank, record RM200 received from a vendor and approve a RM120 personally paid meal. Show bank RM1,200 and claims due RM120. Reimburse the member. Show bank RM1,080, claims due zero, and total meal expense still RM120. Automate this test, plus a test that rejects a repayment above the approved amount. Answers Q1 and Q2. |
| 1b. Events, floats, budgets and VAult accounts | 15–25 hours | Automate the second useful test below. The event page shows budget, adjustments, actuals, variance and cash still with the organizer. Overspending is flagged, not blocked. The VA, Operational Fund, petty cash and cash advance accounts exist, and tests cover the 20%/RM2,000 Operational Fund cap and the petty cash and cash advance limits. The account ledger shows a running balance and exports to CSV. Answers Q3. |
| 2. SoA import and reconciliation | 21–34 hours | Import a fictional SoA in the sample's format. Income shows as negative values, bank charges are recognised, and subtotal rows are skipped. Each line is matched to a ledger movement or flagged, with the variance shown. Importing the same SoA twice changes nothing. Answers Q4 for VA transactions. |
| 3a. PDF parsing | 27–44 hours | Upload a fictionalized version of the post-event report to private storage, with a duplicate check on the file hash. Unit tests on saved text fixtures produce RM343.46 itemized spending across the page break and the RM116.54 remainder, and flag the conflicting summary. |
| 3b. Report review | 32–52 hours | Review the fictional sample row by row: match existing, create, skip or hold. Match the existing advance. Nothing reaches the ledger yet. |
| 3c. Posting and re-import | 36–59 hours | Post only confirmed rows. Re-import the same PDF and verify that posted counts and balances stay the same. A revised export is compared with prior posted rows. Answers Q4 for post-event reports. |
| 4. VAult paperwork | 44–73 hours | Disbursement requests move through their statuses with the next person and due date shown. The IRF, Petty Cash Statement, Cash Advance Statement and Collection Listing export as Excel files in the school's layouts, with NRIC and bank account cells left blank. The deadlines dashboard lists what is due this week. Answers Q5. |
| 5. Treasurer pilot and handover | 52–88 hours | Test several reports and one club-wide outing. Cover partial reimbursements, revised reports, unresolved rows, a cash advance and its return, and transactions crossing term dates. Reconcile one completed event and the VA against a real cycle of SoAs. Produce the handover pack: latest VA balance and Operational Fund, open requests and advances, unreconciled items, upcoming deadlines, and a guide mapping each VAult step to a tracker screen. Rehearse backup and restore with fictional data. |

**Future stage: mobile access.** Start this after the pilot, once the committee agrees who owns and pays for hosting, so it survives treasurer handovers. The tracker stays a web app, so no separate iPhone or Android app is needed.
- Host it on a club-owned account with HTTPS.
- Add treasurer and reviewer logins before it goes online.
- Back up the server using the same tested backup-and-restore procedure.
- Let users upload receipt photos straight from the phone camera, stored privately.
- Make it installable from the browser to the home screen as a Progressive Web App (PWA).
- Prioritise phone tasks: logging an expense at an event, attaching a receipt, and checking claims due, cash with organizers and this week's VAult deadlines. SoA reconciliation, report review and form exports stay laptop tasks.

Completion check: on a phone over HTTPS, a reviewer signs in, records a fictional expense with a receipt photo, and sees it on the laptop.

The VAult work adds about 13–20 hours to the original 40–65. If time runs short, stages 3a–3c (the post-event PDF importer) can wait: SoA reconciliation covers the money that passes through the VA, and post-event reports can be entered by hand.

**A second useful test:** transfer RM300 from a RM1,000 bank balance to an event cash holder. The account view should show RM700 in the bank and RM300 with the organizer. After RM160 of fees and RM343.46 of purchases from that cash, the organizer holds RM116.54. A confirmed return of that amount gives a bank balance of RM816.54 and event cash of zero. This example assumes all fees and purchases pass through the organizer.

## Handover and portfolio

For the first term, provide a short setup guide, a backup and restore procedure tested with fictional data, and exports for opening/closing balances and outstanding claims. The handover pack from stage 5 gives the next treasurer the VAult picture in one place: the latest VA balance from the SoA, the Operational Fund, open requests and cash advances, unreconciled items, upcoming deadlines, and a guide mapping each VAult step to a tracker screen. A later committee deployment can add club-owned hosting, treasurer and reviewer accounts, and access transfer between terms. Revisit those choices with the next treasurer.

For JurisTech, the first demo should show one PHP form submission, the SQL records it changes, and a test that prevents a reimbursement from counting the expense twice. The longer-term success measure is practical: the next treasurer can explain the closing balance and every unpaid claim from the handover records.
