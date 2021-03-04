#Digital Rocket

##How to use

- Clone the repository with __git clone__
- Copy __.env.example__ file to __.env__ and edit database credentials there
- Run __composer install__
- Run __php artisan key:generate__
- Run __npm install__ 
- Run __npm run dev__ 
- Create a database and add database name and username in your .env file:
    ```
    DB_DATABASE=db_name
    DB_USERNAME=username
    ```
- Import data(sql query) to database
- Add the following variables to your .env file as well:
    ```
    DO_SPACES_BUCKET=
    DO_SPACES_ENDPOINT=
    DO_SPACES_KEY=
    DO_SPACES_REGION=
    DO_SPACES_SECRET=
    ```
- Run __php artisan serve__ 
- open http://127.0.0.1:8080.