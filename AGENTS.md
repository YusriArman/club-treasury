# Club Treasury Tracker: conventions

Laravel 13 app (PHP 8.5, MySQL, Blade, Tailwind) for a student club treasurer.
The full plan is in docs/PLAN.md. Progress notes are in docs/progress.md.
Several AI assistants work on this repo, so these rules define the style, not the assistant.

## Commands
- Tests (MySQL database treasury_test): php artisan test
- Static analysis: vendor/bin/phpstan analyse
- Formatting: vendor/bin/pint
- All three must pass before every commit.

## Code rules
- Money is integer sen. Columns end in _sen. Parse and format RM only through the shared money helper; never use floats.
- Validation lives in Form Request classes. Controllers stay thin.
- Anything that writes money is one action class in app/Actions, wrapped in a database transaction.
- Laravel naming defaults: plural snake_case tables, singular models.
- Tests use PHPUnit, not Pest.
- Pages are mobile-first with Tailwind.
- Comments explain why, not what.
- Read a similar existing class before writing a new one, and follow it.
- Do not reformat, rename or restructure code the task does not need to change.

## Ledger rules
1. Expenses record spending; movements record where money moved. A claim repayment is a movement, never an expense.
2. Posted movements are never edited or deleted. Corrections are a reversal plus a replacement, linked with a reason.
3. Claims keep claimed and approved amounts. Over budget is flagged, not blocked.
4. Repayments never exceed the approved amount: lockForUpdate inside a transaction, plus a CHECK constraint.
5. VAult limits (Operational Fund, petty cash, cash advance) are checked when a request is drafted.

## Data and privacy
- Only fictional data in the repository, seeders, fixtures and tests.
- Real receipts, PDFs and SoAs go in storage/app/private, which is never committed.
- Never store NRIC/passport numbers or personal bank account numbers.

## Migrations
- Never edit a migration that has been committed. Change the schema with a new migration.
- An uncommitted migration that already ran locally: `php artisan migrate:rollback`, edit, then `php artisan migrate` again. Editing alone does nothing, because `migrate` skips migrations already recorded as run.
- Money columns are `bigInteger` (signed where direction matters) and end in `_sen`.
- No stored balance columns. Balances are always `SUM(amount_sen)` over movements.

## Milestone 1 build order
Build one table at a time, each with its model, and let the treasurer run every step:
1. `accounts`: name, type (`virtual_account`, `operational_fund`, `petty_cash`, `cash_advance`), is_active.
2. `movements`: account_id (foreign key), occurred_on date, signed amount_sen, description, reference, reverses_movement_id for corrections.
3. `people` and `categories`.
4. `expenses`: amount_sen, category, payer (person or account), event optional, evidence path.
5. `claims` (claimed_amount_sen, approved_amount_sen, status) and `claim_payments` (each linked to one movement).
6. Money helper (string RM to sen and back), then action classes in app/Actions.
7. Forms and a dashboard showing bank balance and claims due.
8. The milestone 1 acceptance test from docs/PLAN.md, plus over-repayment and no-double-count tests.

## Working with the treasurer
- The treasurer is learning PHP and SQL. Explain changes, give commands for them to run, and leave one small change for them to implement in each stage.
- The treasurer runs commands and edits files themselves. Give one command per code block, explain what it does and what output to expect, then wait for them to report back. Only run or edit things yourself when asked.
- Show the SQL behind each Laravel step (for example `DESCRIBE accounts`), because explaining the database is the point of the project.
- Mark placeholders clearly, such as `<YOUR-GITHUB-NOREPLY-EMAIL>`, so they are not typed in word for word.
- Commits carry only the treasurer's authorship: no AI co-author or attribution lines.

## Local environment
- macOS with zsh. An unquoted `!` in a command triggers history expansion ("event not found"); quote it or avoid it.
- MySQL CLI: use `mysql -u root treasury -E -e "..."` for vertical output; this MySQL version rejects `\G` inside `-e`.
- Local MySQL root has no password. Fine for fictional data; secure it before any real data.

## Every session
- Start: read this file, the latest docs/progress.md entry and docs/PLAN.md, then run the tests. The latest progress entry says exactly where to resume.
- End: tests, PHPStan and Pint pass, a commit is made, and docs/progress.md has a new entry (what changed, what's next, open decisions, which assistant helped).
