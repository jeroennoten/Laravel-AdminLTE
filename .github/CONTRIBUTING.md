## Contributing to Laravel-AdminLTE

Contributions are always welcome, we suggest you to read the next lines in order to help us improve this package. The basic recommended flow to submit a **Pull Request** is:

1. Create a [Fork](https://docs.github.com/en/github/getting-started-with-github/fork-a-repo) of this repository using the _Github_ user interface.
2. Use `git clone https://github.com/<your-username>/Laravel-AdminLTE.git` to clone the forked repository locally.
3. In your local environment, create a new branch with a descriptive name and commit your code changes there.
4. Use the `git push origin <your-branch-name>` command to push your branch into your _Github_ fork.
5. Finally, open a **Pull Request** from the new branch of your fork using the _Github_ user interface.

Refer to [this guide](https://help.github.com/articles/about-pull-requests/) for more information about **Pull Requests**, and to [this guide](https://docs.github.com/en/github/collaborating-with-pull-requests/working-with-forks) for information about **Forks**. Also, when submitting a **Pull Request** take the next notes into consideration:

- Review all the automated tests that are triggered when submitting your **Pull Request**.
- Always check that the **Pull Request** doesn't introduce a high downgrade on the code quality. For this, you need to review the automated **Scrutinizer CI** test.
- If the **Pull Request** introduces a new feature, consider adding a proposal of the documentation for the [Wiki](https://github.com/jeroennoten/Laravel-AdminLTE/wiki) into the **Pull Request** description.
- Please, keep the package focused and do not add support to other packages or very particular situations. These changes will make the package harder to maintain.

## Testing your changes.

After you have cloned the forked repository locally and you have made some work on a new branch, before submitting a new **Pull Request**, you may run the package tests to ensure nothing will get broken by your changes. You can follow the next guide to run the tests:

1. On your local git repository, execute `composer install` to install all the package dependencies. The package dependencies will be installed into the `vendor` folder. You may need to install the [composer](https://getcomposer.org/) tool first.
2. Then, run `php -dxdebug.mode=coverage vendor/bin/phpunit --coverage-html <build-folder>` to run all the package tests and create coverage result files into the folder `<build-folder>`.
3. If all the tests succeeded, you can access the coverage results from your browser using the link `file:///<full-path-to-build-folder>/index.html`.
4. Review the coverage results, since you may need to create new tests (or change current ones) to cover your modifications.

Please, do not commit the `vendor` or `<build-folder>` into your **Pull Request**. Also, note that all the package tests are grouped into the `tests` folder.

> [!Tip]
> To keep the package dependencies up-to-date before running the tests, you can execute `composer update` from time to time.

> [!Tip]
> Depending on your local environment, you may need to change the package distributed `phpunit` configuration. For this, first create a copy of `phpunit.xml.dist` named `phpunit.xml` and then make the changes in this new file. An example case of this, is when `phpunit` requires you to migrate the configuration because you are validating to an old schema, this can be resolved by executing:
> ```sh
> cp phpunit.xml.dist phpunit.xml
> vendor/bin/phpunit --migrate-configuration
> ```
