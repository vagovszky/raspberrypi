# ----------------------------------------------------------------
# Copyright Stephen Stebbing 2014
# $Id: $
# ----------------------------------------------------------------
SOURCES=lcdi2c.c lcd_i2c.c

INCLUDES=

# XXX _GNU_SOURCE defined here to prevent warning about vasprintf() in lcd_i2c.c
DEFS=-D _GNU_SOURCE

BIN=lcdi2c
# ----------------------------------------------------------------
CC=gcc
CFLAGS=-std=gnu99 -Os -I. -Wall 

LDFLAGS=-lwiringPi

OBJS=$(SOURCES:.c=.o)

# ---------------------------------------------
all: $(BIN)

%.o: %.c %.h 
	$(CC) -c $(CFLAGS) $(DEFS) -I . -o $@ $<


$(BIN): $(OBJS)
	$(CC) $(DEFS) $(LDFLAGS) $(CFLAGS) -o $(BIN) $(OBJS)

clean:
	rm *.o $(BIN)