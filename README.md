# Crowd Auth Bundle

This bundle allows Symfony apps to authenticate users against Atlassian Crowd.

[![Build Status](https://travis-ci.org/seiffert/crowd-auth-bundle.png?branch=master)](https://travis-ci.org/seiffert/crowd-auth-bundle)

## Installation

Require the package via composer:

`composer.json`:

        "require": {
            ...
            "seiffert/crowd-auth-bundle": "dev-master",
            ...
        }

Activate the bundle and it's dependent bundle `SeiffertCrowdRestBundle` in your AppKernel:

`app/AppKernel.php`:

        public function registerBundles()
        {
            $bundles = array(
                ...
                new Seiffert\CrowdRestBundle\SeiffertCrowdRestBundle(),
                new Seiffert\CrowdRestBundle\SeiffertCrowdAuthBundle(),
                ...
            );
            ...
        }

## Configuration

To connect to your organization's Crowd instance, you have to add some entries to your project configuration (e.g. in
`app/config/config.yml`):

    seiffert_crowd_rest:
        url: https://<crowd-url>/crowd/rest/usermanagement/1
        application:
            name: <application-name>
            password: <application-password>

* **crowd-url**: Your Crowd instance's Url/Hostname.
* **application-name**: The name of your application registered in Crowd.
* **application-password**: The password of your application registered in Crowd.

To use the bundle's authentication provider, you can use it as an extension for Symfony's `SecurityBundle`.
In your project's `security.yml`, you need to configure a plaintext password encoder, the bundle's user provider and
use the key `crowd_login` insteadof `form_login` in your firwall definition. Everything else works exactly as with a
normal form login: You decide about URLs and the login form.

**Example `security.yml`:**

    security:
        encoders:
            PS\CrowdRestBundle\Crowd\User: plaintext

        role_hierarchy:
            ROLE_ADMIN:       ROLE_USER
            ROLE_SUPER_ADMIN: [ROLE_USER, ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]

        providers:
            crowd:
                id: ps.crowd_auth.user_provider

        firewalls:
            dev:
                pattern:  ^/(_(profiler|wdt)|css|images|js)/
                security: false

            login:
                pattern:  ^/auth/login$
                security: false

            secured_area:
                pattern:    ^/
                crowd_login:
                    check_path: /auth/check
                    login_path: /auth/login
                logout:
                    path:   /auth/logout
                    target: /

        access_control:
            - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/, roles: ROLE_USER }

