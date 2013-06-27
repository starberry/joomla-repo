MAPS=map_statics.dbm

all: $(MAPS)

%.dbm: %.txt
	httxt2dbm -i $< -o $@
