COMPOSER=composer

RM=rm -rf

DB=overhemd.db
SQLITE=sqlite3

CSSDIR=public/assets/css

REACTOR=php app/reactor

PORT=8002

all: vendor

clean:
	$(RM) composer.lock
	$(RM) vendor/

vendor: composer.lock $(COMPOSERFILE)
	$(COMPOSER) install
	@touch vendor/

composer.lock: composer.json $(COMPOSERFILE)
	$(COMPOSER) update

server: all
	$(REACTOR) server --port=$(PORT)

server-public: all
	$(REACTOR) server --port=$(PORT) --address=0.0.0.0

felt-db:
	# make -C ../felt newdb-psql
	echo "INSERT INTO commissies (naam, langenaam) VALUES ('foobardb', 'Het DB');" | psql
	$(eval COMMISSIE_ID=$(shell echo "SELECT id FROM commissies WHERE naam='foobardb';"| psql -tA))
	echo "INSERT INTO commissies_personen VALUES (1, $(COMMISSIE_ID));" | psql
