# Club Treasury Tracker

A web app for tracking a student club's money: member claims, reimbursements,
event spending and the club's university-held funds. I'm building it because I'm
the treasurer of Taylor's Agents of Tech, and spreadsheets weren't keeping track
reliably.

**Status:** in progress. The database foundation (accounts and money movements)
is done; expenses, claims and the dashboard are next.

## Design rules
- **Money is stored as whole sen** (RM 84.40 → 8440), never as decimals,
  so totals never drift by a fraction of a cent.
- **Balances are never stored.** A balance is always the sum of the money
  movements on an account.
- **Posted entries are never edited.** A mistake is corrected with a reversing
  entry, so there is always an audit trail.
- **Spending and money movement are separate records**, so repaying a member's
  claim never counts the same expense twice.

## Stack
PHP 8.5 · Laravel 13 · MySQL · PHPUnit · PHPStan (Larastan) · Pint

## Running it locally
1. `composer install`
2. `cp .env.example .env` and `php artisan key:generate`
3. Create two MySQL databases: `treasury` and `treasury_test`
4. `php artisan migrate`
5. `php artisan test`

All data in this repository is fictional.