#!/bin/sh
[ "$1" == "" ] && echo Gebruik: $0 tagnummer && echo Dit tagnummer moet ook in de readme staan. && exit;
WRK=~/Desktop/cookie-opt-in-export-$$
TRN=~/Desktop/cookie-opt-in-trunk-$$
TAG=$1
WPP=http://clearsite@plugins.svn.wordpress.org/cookie-opt-in
D=` dirname "$0" `
[ "$D" == "." ] && D=` pwd `

consistency_check () {
  cd $WRK;
  RMV=` cat readme.txt | grep "Stable tag:" | head -n 1 | awk -F':' '{print $2}' | egrep -o "[0-9\.]+" | awk '{print $1}' `
  [ "$RMV" != "$TAG" ] && echo ReadMe.txt tag mismatch : $RMV should be $TAG && exit;
  RMV=` cat wp_cookie_opt_in.php | grep "Version:" | head -n 1 | awk -F':' '{print $2}' | egrep -o "[0-9\.]+" | awk '{print $1}' `
  [ "$RMV" != "$TAG" ] && echo plugin-file version mismatch : $RMV should be $TAG && exit;
}

export_and_clean_to_temp () {
  cd $D
  svn up
  svn export . $WRK/
  cd $WRK/
  # clearsite variant verwijderen
  # rm js/cls-*
  # rm css/cls-*
  # drupal en static verwijderen
  rm drupal_*
  rm static.php
  rm js/cookie-opt-in-config.js
  rm js/cookie-opt-out-piwik.js

  # verwijder de gestripte variant
  rm wp_cookie_opt_in_cls.php
  rm wp_cookie_opt_in_piwik.php
  rm export-for-wordpress.sh
}

get_wordpress_trunk () {
  svn checkout "$WPP/trunk" "$TRN"
}

# use: update_trunk_with_export "$TRN" "$WRK"
update_trunk_with_export () {
  echo "Now in : $1"
  # overwrite existing files
  IFS=$'\n';
  cd $1;
  for i in ` ls -1 `; do
    echo Now processing: $i
    # file exists in trunk and also in new version ; overwrite
    [ -f "$2/$i" ] && echo cp "$1/$i" "$2/$i";

    # directory exists in trunk and also in new version ; handle recursively
    [ -d "$1/$i" ] && update_trunk_with_export "$1/$i" "$2/$i"

    # file exists in trunk but not in new version ; remove
    [ ! -f "$2/$i" ] && echo svn rm "$1/$i";

    # directory exists in trunk but not in new version ; delete
    [ ! -d "$2/$i" ] && echo svn rm "$1/$i";

  done

  cd $2;
  for i in ` ls -1 `; do
    echo Now reverse processing: $i
    # file/directory exists in new version but not in trunk; copy and add
    [ ! -e "$1/$i" ] && cp "$2/$i" "$1/$i" && svn add "$1/$i";
  done
}

commit_wordpress_trunk () {
  cd $TRN
  svn ci -m "Updated trunk to match Local trunk"
}

tag_wordpress_version () {
  echo svn cp "$WPP/trunk" "$WPP/tags/$TAG"
}

export_and_clean_to_temp
consistency_check
get_wordpress_trunk
update_trunk_with_export "$TRN" "$WRK"
commit_wordpress_trunk
tag_wordpress_version

