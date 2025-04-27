.PHONY: install clean doc

install:
	composer install

clean:
	rm -rf doc var/cache var/log

doc:
	doxygen
