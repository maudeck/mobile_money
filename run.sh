#!/bin/bash
rm -f writable/mobile_money.db
sqlite3 writable/mobile_money.db < base.sql