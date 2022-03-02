# API template Symfony 6

## Set Up project

### Requirements

This project requires Docker is been installed in your system. You can download and install it before the next set up section.
Follow the [Documentation of Docker](https://docs.docker.com/) to install it correctly.

Another dependency is the need of make command, if you are using Windows you may need to install it,
you can follow the next instructions to install:

Using Chocolatey (Only Windows). 
- First you need to [install this package manager](https://chocolatey.org/install). 
- Once installed you simply need to install make (you may need to run it in an elevated/admin command prompt) :
```
choco install make
```
> Solution found in https://stackoverflow.com/questions/32127524/how-to-install-and-use-make-in-windows

### Take care

This project has been developed with a Windows OS, if you are using a different operating system watch out with
those files that runs up this project in local, special mention to:

- [Makefile](makefile)
- [Dockerfile](etc/dev/Dockerfile)
- [Docker-compose.yml](etc/dev/docker-compose.yml)
- [Nginx config files](etc/dev/nginx/)

### Installation

After docker has been installed in your system and make command is in your CLI installed
to set up this project is quiet simple.

Open a terminal and move to root project. After that Run next Command:

```
$ make start
```

After doing these, this command will build Mysql, Php and Nginx images and will set up
it, this command must have been optimized in the future, because it can take it long,
all because each time you use that it rebuild all images with all dependencies.

When the make script finish and all it works fine you project is ready, you can check it with the next link.

    http://localhost

## Commands

This project has a set of commands in Makefile that can use for different proposes:

| make instruction | description                                                                                                              |
|------------------|--------------------------------------------------------------------------------------------------------------------------|
| make swagger     | Crawler that get all OpenApi definitions in the src project and creates a new swagger.json file to refresh documentation |
| make tests       | Run all php unit test, this instruction includes all tests: Unit, Integration and end-to-end tests                       |
| make utests      | Run all unit tests                                                                                                       |
| make itests      | Run all integration tests                                                                                                |
| make e2etests    | Run all end-to-end tests                                                                                                 |
