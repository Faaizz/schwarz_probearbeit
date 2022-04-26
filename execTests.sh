#!/bin/bash

true || mkdir test

docker-compose -f .docker/test.docker-compose.yml up --build --abort-on-container-exit --exit-code-from minicmstest
TEST_RESULT=$?

docker-compose -f .docker/test.docker-compose.yml down
CLEANUP_RESULT=$?

if [ $CLEANUP_RESULT != 0 ]
    then
        echo "ERROR: could not properly clean up test resources"
fi

exit $TEST_RESULT
