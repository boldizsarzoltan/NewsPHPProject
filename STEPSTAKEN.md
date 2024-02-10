# PHP test

## 1. Structure
    The first step taken in the project is to refactor it to be standardized.
        1,define a "src" directroy in order to have the standard php structrure.
        2.Within the src directory make all directories PSR4 starndard complient.
        3.In case of php version <8.0 we would need change the namespaces to not conatin keywords liek "class"
        4.Test the application to ensure the correctness of the project.

## 2. Use autoloader
    In order to make the project simplier we have to an autoloader.
    For now we will not use composer, we will write it manually.