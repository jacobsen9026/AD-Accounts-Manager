# Views
## What is a view?
Views are one of two things. Templates or content.

Views connect the application data to the output the user will eventually see. The entire application is available to an app view (different for system views) so any public data is readily accessible, but keep the necessary data inside the controller method that is rendering the view to keep scopes small.

## Examples
The HTML start and end tags along with the Bootstrap 4 CSS framework is loaded through a template in the system views folder.

The navigation menu is a template that pulls data from the Menu object that renders it.

The homepage is a content segment that contains only div's. It is loaded in-between the app header and footer templates.

## How do I use one?
  
Keep as much processing in the controller as possible, avoid putting heavy logic/calculation in the views themselves.