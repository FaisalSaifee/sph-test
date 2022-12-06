
# Jugaad Patches

Module created for generating QR code of product link


## Steps to enable this module

Install the required library which is used for generating QR code.
```
  composer require endroid/qr-code
```
After intalling library, enable this module

```
  drush en jugaad
```


## Create content

After enableing the module you can create `Products`.
At the time of creating this module I have used `Bartik` theme and set the block in `second sidebar`

If you are using any other theme and not able to find the block `QR code of current product` then you can go to block layout and place the block as per your requirement.
