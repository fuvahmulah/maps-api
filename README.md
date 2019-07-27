# Fuvahmulah Map API
[![All Contributors](https://img.shields.io/badge/all_contributors-1-orange.svg?style=flat-square)](#contributors)

This repository is the home to the backend api that powers Fuvahmulah Map project. It is based on Laravel 5.8.


### Setup
On terminal.
```bash
git clone git@github.com:fuvahmulah/maps-api.git
cd maps-api
composer install
npm install
cp .env.example .env
``` 

Open `.env` on your favourite editor and set the environment variables. Please note that if you are going to be using social authentication you would have to configure the credentials for them. 

Once you have set the environment variables, you may continue with...
```bash
php artisan key:generate
php artisan migrate
php artisan passport:client
```

Now you will be prompted to set up passport client. Once done you are ready to run the application.

### Seeding Data
You can seed data by running `php artisan db:seed` command.

## Contributors ✨

Thanks goes to these wonderful people ([emoji key](https://allcontributors.org/docs/en/emoji-key)):

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore -->
<table>
  <tr>
    <td align="center"><a href="http://about.me/ajaaibu"><img src="https://avatars3.githubusercontent.com/u/1146627?v=4" width="100px;" alt="Ahmed Ali"/><br /><sub><b>Ahmed Ali</b></sub></a><br /><a href="#review-ajaaibu" title="Reviewed Pull Requests">👀</a></td>
  </tr>
</table>

<!-- ALL-CONTRIBUTORS-LIST:END -->

This project follows the [all-contributors](https://github.com/all-contributors/all-contributors) specification. Contributions of any kind welcome!