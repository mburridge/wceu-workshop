## Section 2

In this section you'll render the FAQs in the front end.

Open `render.php`. Copy the shortcode function from `wceu-faq.php` to this file.

Change it as follows:

```php
<?php

  $args = array(
    'post_type' => 'wceu-faq',
    'posts_per_page' => '-1'
  );

  // tax-query removed

  $faqs = new WP_Query( $args );

  $show_faqs = '<div ' . get_block_wrapper_attributes() . '>'; // class changed

  if ( $faqs->have_posts() ) {

    while ( $faqs->have_posts() ) {
      $show_faqs .= '<details>';
      $faqs->the_post();
      $show_faqs .= '<summary>' . get_the_title() . '</summary>';
      $show_faqs .= '<div class="faq-content">' . get_the_content() . '</div>';
      $show_faqs .= '</details>';
    }

  }  else {
    $show_faqs .= "No posts found.";
  }

  $show_faqs .= '</div>';

  wp_reset_postdata();
  echo $show_faqs;

?>
```

The block shows all the FAQs on the front end.

Add a `tax_query` property to the `$args` array, with a hardcoded category:

```php
$args['tax_query'] = array(
  array(
    'taxonomy' => 'wceu-faq-cat',
    'field'    => 'slug',
    'terms'    => 'athens'
  )
);
```

Now just the FAQs from that category display in the front end.

Add an attributes object to `block.json`:

```js
"attributes": {
    "category": {
      "type": "string",
      "default": "athens"
    }
  }
```

$attributes is automagically available to `render.php`. Check for the existence of a category and use that instead:

```php
if ( $attributes['category'] ) {
  $args['tax_query'] = array(
    array(
      'taxonomy' => 'wceu-faq-cat',
      'field'    => 'slug',
      'terms'    => $attributes['category']
    )
  );
}
```

Try changing the category default in `block.json` and see how the front end renders different FAQs. Try changing it to a non-existent category.
