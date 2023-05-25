## Section 0

This is the starter project for the workshop. It consists of the following files:

- `wceu-faq.php`
- `css/wceu-faq-styles.css`
- `wceu-workshop.xml`

`wceu-faq.php` is a basic PHP plugin that implements a simple FAQ. It creates a custom post type, `wceu-faq`, and a custom taxonomy, `wceu-faq-cat`.  A function implements a shortcode, `wceu-faq`, which renders the FAQs with each placed within an expandable `details` element.

There is also a function to check for the shortcode in a page or post and conditionally enqueues `wceu-faq-styles.css` only if the shortcode is found.

The repo for this project can be found at https://github.com/mburridge/wceu-workshop

Clone the repo into your `wp-content/plugins` directory with:

```bash
git clone https://github.com/mburridge/wceu-workshop
```

Activate the plugin then import the content from `wceu-workshop.xml`.

Create a page and add a Shortcode block. Put the following shortcode in the Sortcode block:

`[wceu-faq]`

This will render all the FAQs.

Create another Shortcode block and use this shortcode to display FAQs from a single category:

`[wceu-faq category="athens"]`
