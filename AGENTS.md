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

## Working with the treasurer
- The treasurer is learning PHP and SQL. Explain changes, give commands for them to run, and leave one small change for them to implement in each stage.
- Commits carry only the treasurer's authorship: no AI co-author or attribution lines.

## Every session
- Start: read this file, the latest docs/progress.md entry and docs/PLAN.md, then run the tests.
- End: tests, PHPStan and Pint pass, a commit is made, and docs/progress.md has a new entry (what changed, what's next, open decisions, which assistant helped).
