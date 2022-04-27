#!/bin/sh
# Dump directus data to `dbDump.sql`
docker exec -i schwarz_probearbeit_database_1 pg_dump -U symfony -F p app > .docker/dbDump.sql
