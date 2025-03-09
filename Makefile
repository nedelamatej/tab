.PHONY: clean doc

clean:
	rm -rf doc var/cache var/log

doc:
	doxygen
