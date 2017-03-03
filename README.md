# plg_rsfp_imageresize
Joomla 3.x RSForm!Pro Plugin to automatically resize an image after it is uploaded through a file upload field.
You can define multiple image sizes so that with Responsive Design you can use different file formats.

Settings can be managed through the RSForm!Pro configuration screen.

## Requirements

- Joomla 3.5.0 or higher (for security always use the latest version)

- RSForm!Pro 1.50.0 or higher

## Installation instructions

* Download the [Installer file](https://github.com/renekreijveld/plg_rsfp_imageresize/blob/master/source/plg_rsfp_imageresize/plg_rsfp_imageresize.zip?raw=true)

* Install through the normal Joomla extension installer

* Publish the System - RSForm!Pro Image Resize plugin after installation

* Go to Components > RSForm! Pro > Configuration > Image resize and modify the settins to your liking

* If you have set Log image resizes to 'Yes' a logfile will be created in your log directory (see Global Configuration > System > Path to Log Folder)

* Create or modify a form and add a file upload field

* Test the form and upload an image file

* For every file size you have allowed a resized file will be created with the -tn (thumbnail), -xs (extra small), -sm (small), -md (medium) or -lg (large) addition in the filename.

**Credits:**
* **EventViva** for their **php-image-resize** library (License MIT): https://packagist.org/packages/eventviva/php-image-resize
* **Peter Martin** for **example code** on image resizing and how to use composer
