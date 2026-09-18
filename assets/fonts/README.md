# Lazzer webfont files

The site's type stack leads with **Lazzer**, a commercial typeface from
[Displaay Type Foundry](https://displaay.net/typeface/lazzer). The font files
are deliberately **not** committed to this repository, because the licence is
per-project and is not ours to redistribute.

## What to drop in here

Export a **web** kit (`.woff2`) from your Lazzer licence and place these five
files in this directory, named exactly as below - `assets/css/app.css`
references them by name:

| File                         | Weight | Style  |
| ---------------------------- | ------ | ------ |
| `Lazzer-Regular.woff2`       | 400    | normal |
| `Lazzer-RegularItalic.woff2` | 400    | italic |
| `Lazzer-Medium.woff2`        | 500    | normal |
| `Lazzer-SemiBold.woff2`      | 600    | normal |
| `Lazzer-Bold.woff2`          | 700    | normal |

No code change is needed once the files are in place - the `@font-face` rules
at the top of `assets/css/app.css` pick them up, and `font-display: swap`
means the first paint is never blocked waiting for them.

## Until then

The stack falls through to Inter, which the page already loads from Google
Fonts, so the site keeps its intended proportions with or without Lazzer.
Once Lazzer is installed you can drop the two `fonts.googleapis.com`
`<link>` tags from `includes/layout/header.php` to remove that request.

## Licensing note

Only add files you hold a valid web licence for, and check that the licence
covers the site's monthly pageviews - most foundries, Displaay included, tier
webfont pricing by traffic.
