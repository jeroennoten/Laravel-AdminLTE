## Contributing to Laravel-AdminLTE

Contributions are always welcome, we suggest you to read next lines in order to help us improve this package. The basic recommended flow to submit a **Pull Request** is:

1. [Fork](https://docs.github.com/en/github/getting-started-with-github/fork-a-repo) this repository using the _Github_ interface.
2. Use `git clone https://github.com/<your-username>/Laravel-AdminLTE.git` to clone the forked repository locally
3. Create a new branch and commit your new/updated code there.
4. Use `git push origin <your-branch-name>` to push your branch into _Github_.
5. Finally, open a **Pull Request** from your new branch using the _Github_ interface.

Refer to [this guide](https://help.github.com/articles/about-pull-requests/) for more information about **Pull Requests**, and to [this guide](https://docs.github.com/en/github/collaborating-with-pull-requests/working-with-forks) for information about **Forks**. Also, when submitting a **Pull Request** take the next notes into consideration:

- Review all the automated tests that triggers when submitting your **Pull Request**.
- Always check that the **Pull Request** don't introduce a high downgrade on the code quality. For this one, you need to check the automated **Scrutinizer CI** test
- If the **Pull Request** introduces a new feature, consider adding the related documentation on the [Wiki](https://github.com/jeroennoten/Laravel-AdminLTE/wiki).
- Please, keep the package focused, do not add support for other packages or very particular situations. These changes will make the package more hard to maintain.

## Testing your changes.

After you have cloned the forked repository locally and put some work on your new branch, you can run the package tests before submitting a new **Pull Request** to ensure nothing will be broken by your changes. You may follow the next guide to run the tests:

1. On your local git repository, execute `composer install` to install all the dependencies. Dependencies will be installed into the `vendor` folder. You may need to install [composer](https://getcomposer.org/) tool first.
2. Then, run `vendor/bin/phpunit --coverage-html <build-folder>` to run all the package tests and create coverage files into the folder `<build-folder>`.
3. After all the tests succeed, you can access the coverage results from your browser using next link `file:///<full-path-to-build-folder>/index.html`.
4. Review the coverage results, since you may need to create new tests (or change current ones) to cover your modifications.

Please, do not commit the `vendor` or `<build-folder>` in your **Pull Request**. Also, note all the package tests are grouped into the `tests` folder.

To keep the dependencies up-to-date before running the tests, you can execute `composer update` from time to time.
