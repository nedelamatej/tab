.PHONY: install clean doc

install:
	composer install

clean:
	rm -rf doc

doc:
	doxygen
