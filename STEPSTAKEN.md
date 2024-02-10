# PHP test

## 1. Structure
    The first step taken in the project is to refactor it to be standardized.
        1,define a "src" directroy in order to have the standard php structrure.
        2.Within the src directory make all directories PSR4 starndard complient.
        3.In case of php version <8.0 we would need change the namespaces to not conatin keywords like "class".
        4.Test the application to ensure the correctness of the project.
        5.It is a bad practice to not respect the standard structure of a project, a non standard projec is hard to read.

## 2. Use autoloader
    In order to make the project simplier we have to an autoloader.
    For now we will not use composer, we will write it manually.
    In a bigger pject we would use a simplier composer dependecy, liek symfony autoloader.
    WIth the autoloader we can actually delte all the requires from the project.

## 3. Database connection
    The first step in such a prject is to see if there is hardcoded database conenction which in our case there is.
    We have to ensure that this information does not make it to git, this a security vulnerability.
    We have to read it from the enviorement, and the enviorement, in out case ".env" does not make it to git.
    In order to load enviorement vraibles we use a nwe class, in order to se this class we create a separate file as a setup.
    The standard setup file is "bootsrap.php" we will go by this standard.

## 4. Separation the project into layers
    Each prject needs layers, so we will restructure the rpject into multiple layers.
    THe main ones will be:
        1.Database
        2.Database Managers
        3.Entitities
        4.Utils
    We will rename the DB class into DatabaeeConnection in order to clarify what it does.
## 5.Improve the general quality of code:
    Remove teh usage of __CLASS__ and use self/static instead.
    Add types where possible.
    Remove uncecessary varaibles.
    Avoid using database "exec", use prepared statements.
    We will use avbstaction for fursther abstraction setps.
    We will extract an interface from the database, in order to break direct dependencies.