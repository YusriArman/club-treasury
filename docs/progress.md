# Progress

## 2026-11-20 · Milestone 0 (setup) complete
**Assistant:** Claude Opus 5.5
**Changed:** Installed PHP 8.5, Composer, MySQL and poppler with Homebrew. Created the Laravel 13 app, MySQL databases treasury and treasury_test, git repository, Larastan (level 5), AGENTS.md conventions and this log.
**Next:** Milestone 1, the core ledger demo: accounts, movements, expenses, claims, repayments, dashboard.
**Open decisions:** Which instructions file Sol 6.2 reads. MySQL root has no password (fine locally; secure it before real data).

## 2026-11-23 · Milestone 1 started (handover to Codex)
**Assistant:** Claude Opus 5.5
**Changed:** Created the `Account` model and the `create_accounts_table` migration (id, name, type VARCHAR(30), is_active default true, timestamps). Migrated to MySQL `treasury` and verified with `DESCRIBE accounts`. Added migration rules, the milestone 1 build order, treasurer working style and local environment notes to AGENTS.md.
**State:** Not committed yet. Tests and PHPStan pass. Pint fails on the accounts migration (a space before `->default` and class brace formatting).
**Resume here:**
1. Treasurer runs `vendor/bin/pint` to fix the formatting, then `vendor/bin/pint --test` to confirm.
2. Commit: `git add .` then `git commit -m "Add accounts table and model"`.
3. Next table: `movements` (step 2 of the milestone 1 build order in AGENTS.md). Explain foreign keys, signed `amount_sen` and why posted movements are never edited.
**Open decisions:** none new.

## 2026-11-23 · Milestone 1 movements table
**Assistant:** Codex
**Changed:** Added the `Movement` model and migrated the `movements` table in `treasury`. Verified with `SHOW CREATE TABLE movements` that `amount_sen` is signed, `account_id` references `accounts.id`, and the optional `reverses_movement_id` references `movements.id`.
**Checks:** PHPUnit passed (2 tests, 2 assertions). PHPStan and Pint passed.
**Next:** Build the `people` table and its `Person` model, then move on to `categories`.
**Open decisions:** None.
