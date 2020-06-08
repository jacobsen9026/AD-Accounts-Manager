# Routing

## What is a router?
The router class takes the incoming request (eg: http://localhost/test/show/all) and breaks it apart into a "routable" path for the app. In the example provided, the app would use the class named "test", create an object of it, and call the method (function) show, passing the parameter of "all". Any further extensions to the requested URI are ignored.

All components of a route are optional, and are replaced with the defaults below if not provided. These defaults can be overrided in the Router.php file in app/config.

## Dealing with dashes
If dashes (-) are used for an internal link the router will handle it by removing the dash and capitalizing the next letter. It will do this for both the controller and the method. For example, /account-status would become accountStatus.

## Default Parameter
If no parameter is supplied, none is given to the called class->method.

## Default Method
If no method is supplied, index will be used, unless otherwise specified.

## Default Class
If no class is supplied, the Home class is used, unless otherwise specified.