#!/bin/sh
ARCHIVE_NAME="de.packageforge.wbb.postmarking.tar.gz"

# remove old packages
test -e files.tar && rm files.tar
test -e templates.tar && rm templates.tar
test -e acptemplates.tar && rm acptemplates.tar
test -e pip.tar && rm pip.tar
test -e $ARCHIVE_NAME && rm $ARCHIVE_NAME

# building new package
test -e files && cd files && tar -cf ../files.tar * && cd ..
test -e templates && cd templates && tar -cf ../templates.tar * && cd ..
test -e acptemplates && cd acptemplates && tar -cf ../acptemplates.tar * && cd ..
test -e pip && cd pip && tar -cf ../pip.tar * && cd ..
tar -czf $ARCHIVE_NAME * --exclude files --exclude templates --exclude acptemplates --exclude pip --exclude README* --exclude build.sh
mv $ARCHIVE_NAME ~

# clean up
test -e files.tar && rm files.tar
test -e templates.tar && rm templates.tar
test -e acptemplates.tar && rm acptemplates.tar
test -e pip.tar && rm pip.tar