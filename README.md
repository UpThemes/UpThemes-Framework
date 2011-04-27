# Welcome to the UpThemes Framework, a GPL 2.0-licensed theme options framework for WordPress.

To get started using the framework, you simply need your own WordPress theme.

## Implementation Instructions

1. Grab the latest stable release of the UpThemes Framework from the /tags/ folder.
2. Drop the /admin/ and /theme-options/ folders into your theme folder.
3. Add the following line to your functions.php file:

        require_once('admin/admin.php'); // bootstrap the UpThemes Framework

4. Copy /admin/options-tab-sample.txt to your /theme-options/ folder and name it something like general-options_1.php

    > **NOTE ON NAMING OPTIONS FILES:** Each theme options file will create a new tab in your theme options panel. As shown in the name "general\-options\_1.php" the name of each file is very important in naming the tab as well as setting the  order of the tabs. The 1 at the end of the filename is what tells the UpThemes Framework what order to show the tab in. Make sure to separate words in a name of a tab by using dashes (\-) and separate the words from the order by using underscores(\_). The underscore separates the tab name from the order number of each tab file. I know it sounds confusing, but trust me, it makes your life easier later when you're building out new tabs.

5. Within each tab file, you need to define your options. At the top of each file is a list of options and you should start off with an example of how the array should be structured. Follow that structure and you should be golden.

6. Once you've created all your theme options, you need to call the global variable $up_options wherever you plan to use them, like so:

        function my_theme_logo(){
           global $up_options;
        ?>
           <a href="http://mywebsite.com"><img src="<?php echo $up_options->theme_logo; ?>"></a>
        <?php
        }

    You simply declare the global `$up_options` and then use the id of the theme option you created previously and echo `$up_options->theme_logo`.

7. Have fun! For more advanced examples, [download some themes](http://upthemes.com/category/themes/) from UpThemes and take a peek under the hood.
