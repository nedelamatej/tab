/*
 * Bakalarska prace
 * MODELOVANI A ANALYZA TRAJEKTORII SOFTBALLOVEHO NADHOZU
 *
 * Vysoke uceni technicke v Brne
 * Fakulta informacnich technologii
 * Ustav pocitacove grafiky a multimedii
 *
 * Autor:   Matej Nedela
 * Vedouci: Ing. Tomas Milet, Ph.D.
 */

/**
 * @file
 * @brief Database teardown script
 *
 * @author Matej Nedela
 */

ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA732C8A3DE;
ALTER TABLE pitch DROP FOREIGN KEY FK_279FBED971F7E88B;
ALTER TABLE pitch DROP FOREIGN KEY FK_279FBED99A04123C;
ALTER TABLE pitch DROP FOREIGN KEY FK_279FBED9C54C8C93;
ALTER TABLE pitcher DROP FOREIGN KEY FK_9B7B5A4F32C8A3DE;

DROP TABLE event;
DROP TABLE organization;
DROP TABLE pitch;
DROP TABLE pitcher;
DROP TABLE type;
