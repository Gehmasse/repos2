# Repo Viewer

Laravel command line application to load repositories from GitHub and sort them into folders.

-   install PHP 8.4
-   run `composer install`.
-   generate a GitHub token with access to repository metadata at [https://github.com/settings/personal-access-tokens](https://github.com/settings/personal-access-tokens)
-   add `GITHUB_USER` and `GITHUB_TOKEN` to your `.env` file.
-   add `REPO_LOCATION` to `.env`; this is an absolute path to the local folder where all your repositories should be stored
-   run `php artisan repos` to view available commands
