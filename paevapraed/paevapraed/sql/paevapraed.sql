-- phpMyAdmin SQL Dump
-- version 3.3.10.1
-- http://www.phpmyadmin.net
--
-- Masin: localhost
-- Tegemisaeg: 14.06.2011 kell 11:16:49
-- Serveri versioon: 5.0.92
-- PHP versioon: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Andmebaas: `marikako_paevapraed`
--

-- --------------------------------------------------------

--
-- Struktuur tabelile `datefoods`
--

CREATE TABLE IF NOT EXISTS `datefoods` (
  `code` varchar(10) character set utf8 collate utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `food` varchar(500) character set utf8 collate utf8_unicode_ci NOT NULL,
  `changedate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `info` varchar(600) character set utf8 collate utf8_unicode_ci default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci;

--
-- Tabeli andmete salvestamine `datefoods`
--

INSERT INTO `datefoods` (`code`, `date`, `food`, `changedate`, `info`) VALUES
('PATTAYA', '2010-01-15', 'Pilaff, marineeritud kurk 55.-<br/>PÄEVASUPP: Grillvorstisupp', '2010-01-16 15:55:20', ''),
('PATTAYA', '2010-01-14', 'Sealiha seentega, Friteeritud kartul, kurgi-kapsasalat 55.-<br/>PÄEVASUPP: Grillvorstisupp', '2010-01-16 15:55:20', ''),
('PATTAYA', '2009-12-13', 'Guljass, kartulipuder, kapsa-porgandisalat 55.-<br/>PÄEVASUPP: Grillvorstisupp', '2010-01-16 15:55:20', ''),
('PATTAYA', '2010-01-12', 'Kooreklops, keedukartul, punapeedisalat 55.-<br/>PÄEVASUPP: Hernesupp', '2010-01-16 15:55:20', ''),
('PATTAYA', '2010-01-11', 'Ahjusaslõkk, ahjukartul, grillsalat 55.-<br/>PÄEVASUPP: Hernesupp', '2010-01-16 15:55:20', ''),
('BARCLAY', '2010-05-21', 'Värskekapsaborš;- Pikkpoiss kartulipüree, magusate porgandite, brokkolija koorekastmega+ kohv / tee;- Bananasplitvaniljejäätisega', '2010-05-25 15:55:11', ''),
('GLAM', '2010-02-22', 'Hetkel info puudub', '2010-02-20 09:42:23', NULL),
('GLAM', '2010-02-20', 'Hetkel info puudub', '2010-02-20 09:19:05', ''),
('GLAM', '2010-02-21', 'Hetkel info puudub', '2010-02-21 20:17:57', 'Info puudub'),
('PLACE', '2010-04-14', 'Sealihast kooreklops ahjukartulite ja praetud sibulaga <br /> Kirsikook', '2010-04-16 11:55:02', 'päevapraad 39.-<br /> päevakook 15.-<br /> päevapraad + päevakook 49.-<br /> päevapraad + karastusjook/kohv 49.-<br /> päevapraad + päevakook + karastusjook/kohv 59.-'),
('AMPS', '2010-05-18', 'Sibulaklops 42.-<br/>Kana-klimbisupp 15.-/18.-<br/>Pannkoogid maasika toormoosiga 17.-', '2010-05-18 17:42:46', ''),
('PLACE', '2010-04-16', '- <br /> -', '2010-04-16 11:55:02', 'päevapraad 39.-<br /> päevakook 15.-<br /> päevapraad + päevakook 49.-<br /> päevapraad + karastusjook/kohv 49.-<br /> päevapraad + päevakook + karastusjook/kohv 59.-'),
('PLACE', '2010-04-15', 'Kanastrogonov keedukartulite ja porgandisalatiga <br /> Kohupiima-purukook vaarikamoosiga', '2010-04-16 11:55:02', 'päevapraad 39.-<br /> päevakook 15.-<br /> päevapraad + päevakook 49.-<br /> päevapraad + karastusjook/kohv 49.-<br /> päevapraad + päevakook + karastusjook/kohv 59.-'),
('PLACE', '2010-04-12', 'Gulja&scaron; keedukartulite ja punapeedi-kodujuustusalatiga <br /> Õuna-pisarakook', '2010-04-16 11:55:02', 'päevapraad 39.-<br /> päevakook 15.-<br /> päevapraad + päevakook 49.-<br /> päevapraad + karastusjook/kohv 49.-<br /> päevapraad + päevakook + karastusjook/kohv 59.-'),
('PLACE', '2010-04-13', 'Hakkkotlet keedukartulite ja värske salatiga <br /> Pirnikook', '2010-04-16 11:55:02', 'päevapraad 39.-<br /> päevakook 15.-<br /> päevapraad + päevakook 49.-<br /> päevapraad + karastusjook/kohv 49.-<br /> päevapraad + päevakook + karastusjook/kohv 59.-'),
('BARCLAY', '2010-05-20', 'Wrap graavitud lõhe ja toorjuustuga;- Kanafilee riisi, köögiviljade ja pähklikastmega+ kohv / tee;- Kohupiimavaht rabarberiga', '2010-05-25 15:55:11', ''),
('BARCLAY', '2010-05-19', 'Kanasalat puuviljadega;- Kooreklops keedukartulite ja marineeritud kurkidega+ kohv / tee;- Mannavaht', '2010-05-25 15:55:11', ''),
('BARCLAY', '2010-05-17', 'Roheline salatbruschettaja kitsejuustuga;- Kurzeme strooganov köömnekartulite ja värske salatiga+ kohv / tee;- Panna cotta', '2010-05-25 15:55:11', ''),
('BARCLAY', '2010-05-18', 'Punapeedicarpaccio vürtsikilu ja pošeeritud munaga;- Ahjušašlõkk ürdikartulite ja tomatisalatiga + kohv / tee;- Tiramisu', '2010-05-25 15:55:11', ''),
('PYSS', '2011-02-28', 'Talupojasupp peekoniga, magus-hapu kana riisiga, pannkoogid mustikatega', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-11', 'Metsaseenesupp, plohv sealihaga, majakook', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-25', 'L&otilde;hesupp, &scaron;nitsel sealihast, rullbiskviit kohupiimaga', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-23', 'Guljashsupp, kana-&scaron;hampinjoni pastaroog, j&auml;&auml;tis &scaron;okolaadikeeksiga', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('REBASE', '2011-02-04', 'Küüslaugune kartuli-seenesupp 1.10<br />\r\nVeisemaksakotlet 2.30<br />\r\nHakklihalasanje 2.30', '2011-01-30 21:52:55', 'NÜÜDSEST PAKUME KA HOMMIKUBUFFEED 8.30-10.00. VALIKUS PUDRUD, SAIAKESED, TOORSALAT, VÄRKSE KOHVI JA TEE!<br />\r\nSOOVI KORRAL TOOME SOOJA LÕUNE OTSE TEIE KONTORISSE!'),
('REBASE', '2011-02-02', 'HISPAANIA KÖÖGI PÄEV!<br />\r\nHispaania värskekapsasupp loomalihaga 1.10<br />\r\nPaella kanaliha ja mereandidega 2.30<br />\r\nHautatud loomaliha Katalooniast 2.30<br />\r\nTortilla mesise puuviljasalatiga 1.10', '2011-01-30 21:52:55', 'NÜÜDSEST PAKUME KA HOMMIKUBUFFEED 8.30-10.00. VALIKUS PUDRUD, SAIAKESED, TOORSALAT, VÄRKSE KOHVI JA TEE!<br />\r\nSOOVI KORRAL TOOME SOOJA LÕUNE OTSE TEIE KONTORISSE!'),
('REBASE', '2011-02-03', 'Kalaseljanka 1.10<br />\r\nÜhepajatoit sealihaga 2.30<br />\r\nPasta bolognese 2.30', '2011-01-30 21:52:55', 'NÜÜDSEST PAKUME KA HOMMIKUBUFFEED 8.30-10.00. VALIKUS PUDRUD, SAIAKESED, TOORSALAT, VÄRKSE KOHVI JA TEE!<br />\r\nSOOVI KORRAL TOOME SOOJA LÕUNE OTSE TEIE KONTORISSE!'),
('REBASE', '2011-01-31', 'Hapukapsasupp peekoniga 1.10<br />\r\nKoduselt koorene kanakaste 2.30<br />\r\nSealihatasku köögiviljatäidisega 3.00', '2011-01-30 21:52:55', 'NÜÜDSEST PAKUME KA HOMMIKUBUFFEED 8.30-10.00. VALIKUS PUDRUD, SAIAKESED, TOORSALAT, VÄRKSE KOHVI JA TEE!<br />\r\nSOOVI KORRAL TOOME SOOJA LÕUNE OTSE TEIE KONTORISSE!'),
('REBASE', '2011-02-01', 'Kana-köögiviljasupp 1.10<br />\r\nTäidetud hakkliharull 2.30<br />\r\nSealiha punases kastmes 2,60', '2011-01-30 21:52:55', 'NÜÜDSEST PAKUME KA HOMMIKUBUFFEED 8.30-10.00. VALIKUS PUDRUD, SAIAKESED, TOORSALAT, VÄRKSE KOHVI JA TEE!<br />\r\nSOOVI KORRAL TOOME SOOJA LÕUNE OTSE TEIE KONTORISSE!'),
('PYSS', '2011-02-22', 'Frikadellisupp, guljash sealihast kartulip&uuml;reega, jogurti tarretis vaarikatega', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-07', 'Rassolnik kana&scaron;a&scaron;l&otilde;kk keefiri marinaadis, toorjuustukook', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-01', 'Selge-kalasupp, kana&scaron;nitsel riisi ja aedviljadega, majakook.', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-02', 'Frikadellisupp, l&otilde;he-kartulivorm, kohupiima-virsikukook', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-03', 'Seljanka, kana poolkoivad riisi ja toorsalatiga, kohupiimavaht kisselliga', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-18', 'Selge kalasupp, kotlet kartulip&uuml;ree ja peedisalatiga, mannavaht piimaga', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-21', 'Hernesupp, grillvorstid kartulilaastude ja v&auml;rske salatiga, pannkoogid kondenspiimaga', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-17', 'Hart&scaron;oo, loomalihast pajaroog k&ouml;&ouml;giviljadega, tosca kook', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-16', 'Seljanka, kanarull paprikaga ja riisiga, ahju&otilde;un j&auml;&auml;tisega', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-15', 'Minestrone supp, hakkliha-kartulivorm juustuga, saiavorm piimaga', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-14', 'Kana-klimbisupp, punases marinaadis sea&scaron;a&scaron;l&otilde;kk, j&auml;&auml;tis kastmega', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-04', 'L&otilde;hesupp, sibulaklops loomalihast keedukartuliga, leivavaht rosinatega', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-09', 'Selge kalasupp, spagetid Bolognese kastmega, marja tarretis vahukoorega', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-10', 'Kana-klimbisupp, guljash sealihast kartulip&uuml;reega, saiavorm piimaga', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('PYSS', '2011-02-08', 'Borsh, seafilee pohlakastmes, kirsi purukook', '2011-03-01 10:15:07', 'Suur Peeter - p&auml;evasupp, p&auml;evapraad, leib-sai.<br/>V&auml;ike Peeter - p&auml;evapraad, p&auml;evamagustoit, leib-sai.<br/>Pisi Peeter - supp, magustoit, leib-sai.<br/>Suure ja V&auml;ikese Peetri hind on 3.80 eurot/59,45 krooni<br/>Pisi Peetri hind on 2.50 eurot /39,12 krooni'),
('TRUFFE', '2011-04-22', 'Kr&otilde;be kanakoib grillsalatiga', '2011-04-24 15:55:11', 'Juurde pakume j&auml;&auml;vett ja ahjusoojad kuklid !!!<br/>Hind 3.77&euro;/ 59.00EEK'),
('TRUFFE', '2011-04-21', 'Paneeritud kalafilee krevetikastmega', '2011-04-24 15:55:11', 'Juurde pakume j&auml;&auml;vett ja ahjusoojad kuklid !!!<br/>Hind 3.77&euro;/ 59.00EEK'),
('TRUFFE', '2011-04-20', 'Kreemjas kastmes loomaliha k&uuml;&uuml;slaugu tatraga', '2011-04-24 15:55:11', 'Juurde pakume j&auml;&auml;vett ja ahjusoojad kuklid !!!<br/>Hind 3.77&euro;/ 59.00EEK'),
('KROOKS', '2011-05-27', 'Kanasa&scaron;l&otilde;kk &uuml;lek&uuml;pse kartul ja toorsalat 3,20 eur', '2011-05-30 09:25:05', ''),
('ASIANCHEF', '2011-05-26', 'Praetud kana magus-hapu kastmes tšilli ja küüslauguga / Peking chicken', '2011-05-27 15:05:13', ''),
('ASIANCHEF', '2011-05-27', 'Roog kookospiima, seente, bambusvõrsete ja sealihaga / Thai red curry pork', '2011-05-27 15:05:13', ''),
('TRUFFE', '2011-04-19', 'V&auml;rskekapsa-hautis kartulip&uuml;reega', '2011-04-24 15:55:11', 'Juurde pakume j&auml;&auml;vett ja ahjusoojad kuklid !!!<br/>Hind 3.77&euro;/ 59.00EEK'),
('TRUFFE', '2011-04-18', 'Kergelt v&uuml;rtsikas sealihapada', '2011-04-24 15:55:11', 'Juurde pakume j&auml;&auml;vett ja ahjusoojad kuklid !!!<br/>Hind 3.77&euro;/ 59.00EEK'),
('ASIANCHEF', '2011-05-25', 'Kalafilee ingveri- ja küüslaugu kastmes / Ginger garlic fish', '2011-05-27 15:05:13', ''),
('YAKUZA', '2011-06-10', 'Iga tööpäev päevapakkumised alates 3.90€', '2011-06-06 20:45:22', 'Lisaks päevapakkumistele, tööpäeviti 12.00-16.00 pakutakse Bento lõunasööki (supp, praad või sushi valik, värske salat, jaapani tee).'),
('ASIANCHEF', '2011-05-23', 'Kanafilee aedviljadega rikkalikus magus-hapu kastmes / Sweet&amp;sour chicken', '2011-05-27 15:05:13', ''),
('ASIANCHEF', '2011-05-24', 'Krõbedad kanafileeribad herne, selleri, mee ja tšilliga / Crispy chicken in honey and chili', '2011-05-27 15:05:13', ''),
('PANDA', '2011-06-17', 'Karri köögiviljad / aurutatud riis / salat - Tükeldatud köögiviljad küpsetatud tugevas küüslaugu-tomati-sibula kastmes, aurutatud riis ja värkse salat<br/>Magus maisi ja kana supp / küüslaugu leib - Paks kana-maisi supp, praetud valge leib, maitsestatud küüslauguga', '2011-06-14 11:15:21', 'Päevapraad hind - 2.80&#8364<br/>Päevasupp hind - 1.50&#8364'),
('PANDA', '2011-06-16', 'Kana seentega / hiinakapsas ja brokkoli / aurutatud riis / salat - Preatud kanalihakuubikud seente, hiinakapsa ja brokkoliga valges kastmes, aurutatud riis ja värske salat<br/>Supp "Panda-eri"/ aurutatud leib - Paks vürtsikas supp krevettide, kanaliha, seente, bambusvõrsete, nuudlite ja juurviljadega, aurutatud leib', '2011-06-14 11:15:21', 'Päevapraad hind - 2.80&#8364<br/>Päevasupp hind - 1.50&#8364'),
('PANDA', '2011-06-15', 'Taeva trummid / Hakka nuudlid köögiviljadega / salat - Mahlased kanalihast "trummipulgad" ingveri, küüslaugu ja sibulaga, nuudlid köögiviljade ja munaga, värske salat<br/>Magusa maisi ja seene supp / küüslaugu leib - Paks maisi-seene supp, praetud valge leib, maitsestutud küüslauguga', '2011-06-14 11:15:21', 'Päevapraad hind - 2.80&#8364<br/>Päevasupp hind - 1.50&#8364'),
('PANG', '2011-06-13', 'Tomati-spinatisupp ja Kana nuudlitega soja-baklazaanik ja astmes ja Sidrunivesi ja 4.15€ / 65 EEK ja PÄEVAKOOK ja pakume nüüd iga päev, ja ka L ja P ja Mündi-sokolaadikook vahukoore kastmega ja 1.28 € / 20 EEK', '2011-06-13 15:55:05', ''),
('ENTRI', '2011-06-14', 'K&ouml;&ouml;givilja-tuunikalasalat 1.70&euro; 26.60 EEK<br/>Klimbisupp kanalihaga 1.50&euro; 23.47 EEK<br/>Hakklihak&auml;bid spinatikastmega 3.00 &euro; 46.94 EEK<br/>1/2 praad 2.00 &euro; 31.29 EEK<br/>Kohupiimavaht virsikukastmega 1.40&euro; 21.91 EEK', '2011-06-14 11:15:11', ''),
('KOTKA', '2011-06-14', 'Seapraad muna-redisekastmega<br/>Värskekapsasupp', '2011-06-14 11:15:13', 'väike praad 38 EEK / 2,43 EUR<br/>suur praad 50 EEK / 3,20 EUR<br/>väike supp 15 EEK / 0,96 EUR<br/>suur supp 20 EEK / 1,28 EUR'),
('SUUDLEVAD', '2011-06-17', 'Riivsaia paneeringus sealihašnitsel, peekoniga maitsestatud kartulipüree, punapeedi - õuna salati ning koorese brokkolikastmega<br/>Keefirisupp veisesingiga<br/>*Tomatisupp ürdikrutoonidega', '2011-06-14 11:15:08', ''),
('YAKUZA', '2011-06-08', 'Iga tööpäev päevapakkumised alates 3.90€', '2011-06-06 20:45:22', 'Lisaks päevapakkumistele, tööpäeviti 12.00-16.00 pakutakse Bento lõunasööki (supp, praad või sushi valik, värske salat, jaapani tee).'),
('TARE', '2011-06-16', 'Praetud kala.keedukartul,<br/>sidruni-kapsasalat.<br/>V&auml;rskekapsabor&scaron;&scaron;.', '2011-06-14 11:15:20', 'KOMPLEKTL&Otilde;UNA<br/>praad, j&auml;&auml;vesi, soe kukkel<br/>3.30 &euro;/51.60 EEK<br/>P&Auml;EVASUPP<br/>1.60 &euro;/25.00 EEK'),
('TARTU', '2011-06-14', 'Maksakaste 2.88 / 1.92 € 45.06 / 30.04 kr<br/>Pozarski kotlet 2.88 / 1.92 € 45.06 / 30.04 kr<br/>Rassolnik 1.60 / 1.00 € 25.03 / 15.65 kr<br/>Virmaline 1.00 € 15.65 kr', '2011-06-14 11:15:02', ''),
('JAMJAM', '2011-06-09', 'Kanafilee kaetud tomati ja juustuga, spagetid, tomati-kurgisalat, kreemine tomatikaste', '2011-06-12 15:55:13', 'P&auml;evaprae hind 2,50 ( 39,12 )<br/>Uudis!!'),
('JAMJAM', '2011-06-10', 'Punases marinaadis grillitud seafilee, kartulip&uuml;ree, k&uuml;lm m&auml;dar&otilde;ika kaste, toorsalat', '2011-06-12 15:55:13', 'P&auml;evaprae hind 2,50 ( 39,12 )<br/>Uudis!!'),
('SUUDLEVAD', '2011-06-13', 'Müsli paneeringus broilerikintsuliha, kartulipüree, porgandi - puuvilja salati ning punase karri kastmega<br/>Frikadellisupp<br/>*Aedviljarisotto röstitud kanafileega<br/>', '2011-06-14 11:15:08', ''),
('MOKA', '2011-06-10', 'Täidetud jahutortillad EUR 4.41<br/>(hakkliha, juurviljade ja kartulipüreegatäidetud jahutortillad, leküpsetatud juustuga, tomatikaste, majasalt)<br/>Majakook', '2011-06-12 15:55:13', ''),
('SUUDLEVAD', '2011-06-15', 'Teriyaki glasuuriga lõhefilee, aedviljariisi, hiinakapsa – kurgi - tomati salati ning külma tar - tar kastmega<br/>Koorene seenepüreesupp<br/>*Kalkuniliha - porrulaugu rulaad, hiinakapsa salati ning külma jogurti - ürdi kastmega<br/>', '2011-06-14 11:15:08', ''),
('SUUDLEVAD', '2011-06-16', 'Ananassi- ja juustukattega broilerifilee, värskete keedetud kartulite, aedvilja salati ning külma ketšupi - majoneesi kastmega<br/>Hapuoblikasupp keedetud muna ja hapukoorega<br/>*Selge kana - aedviljasupp<br/>', '2011-06-14 11:15:08', ''),
('MOKA', '2011-06-07', 'Pasta Carbonara Moka moodi EUR 4.41<br/>( peekoni, sibula, ja koorekastmes spagetid, majasalat )<br/>Majakook', '2011-06-12 15:55:13', ''),
('MOKA', '2011-06-08', 'Loomaliharull seenetäidisega, kõrvitsa-mangochutney EUR 4.41<br/>(täidetud loomaliharull seentega, seene demi glaze, punapeedi kartulitorn, juurviljad, rdid, majasalat)<br/>Majakook', '2011-06-12 15:55:13', ''),
('JAMJAM', '2011-06-06', 'Sealiha snitsel, &uuml;lek&uuml;psetatud kartul, k&uuml;lm roh.sibula kaste, v&auml;rske salat', '2011-06-12 15:55:13', 'P&auml;evaprae hind 2,50 ( 39,12 )<br/>Uudis!!'),
('YAKUZA', '2011-06-09', 'Iga tööpäev päevapakkumised alates 3.90€', '2011-06-06 20:45:22', 'Lisaks päevapakkumistele, tööpäeviti 12.00-16.00 pakutakse Bento lõunasööki (supp, praad või sushi valik, värske salat, jaapani tee).'),
('YAKUZA', '2011-06-06', 'Iga tööpäev päevapakkumised alates 3.90€', '2011-06-06 20:45:22', 'Lisaks päevapakkumistele, tööpäeviti 12.00-16.00 pakutakse Bento lõunasööki (supp, praad või sushi valik, värske salat, jaapani tee).'),
('YAKUZA', '2011-06-07', 'Jaapani tee Sencha<br />\r\nChicago Maki (praetud lõhe, kurk, majonees, seesamiseemned)<br />\r\n2tk. Tamagoyaki (jaapani omletti sushi)<br />\r\n3.90€', '2011-06-06 20:45:22', 'Lisaks päevapakkumistele, tööpäeviti 12.00-16.00 pakutakse Bento lõunasööki (supp, praad või sushi valik, värske salat, jaapani tee).'),
('RANDURI', '2011-06-14', 'Päevapraed: täidetud sealiharull,lasnje,sealiha guljašš<br />\r\nSupp: kana-riisisupp<br />\r\nMagustoit: mannavaht,keefiri-hapukoorekook,kohupiimavaht kisselliga,rabarberikook', '2011-06-14 09:57:47', 'Päevaprae hind 3.20 EUR<br />\r\nPäevapraadi pakume alates kell 12.00<br />\r\npäevasupp 2 EUR<br />\r\nmagustoit 1EUR<br />\r\nBuffee avatud 12.00-14.30'),
('TARE', '2011-06-17', 'Kanapada,risotto,<br/>tomati-kurgi salat.<br/>V&auml;rskekapsabor&scaron;&scaron;', '2011-06-14 11:15:20', 'KOMPLEKTL&Otilde;UNA<br/>praad, j&auml;&auml;vesi, soe kukkel<br/>3.30 &euro;/51.60 EEK<br/>P&Auml;EVASUPP<br/>1.60 &euro;/25.00 EEK'),
('ATLANTIS', '2011-06-17', 'Lambahakkliha kebabi varras, kr&otilde;betate kartulisektorite, marineeritud tomati,kurgi,sibula salati ja sinepi-k&uuml;lma kastmega', '2011-06-14 11:15:12', ''),
('TREHV', '2011-06-17', 'Grillvorstid külma koorekastmega 3€</br>Köögiviljapüree supp 1.5€</br>Jäätis šokolaadiga 1.5€', '2011-06-14 11:15:04', ''),
('AURA', '2011-06-14', 'Lihapallikaste, kartul, punapeedi salat 2.43<br/>Frikadellisupp 1.60', '2011-06-14 11:15:01', ''),
('JAMJAM', '2011-06-08', 'Kanapada aedviljadega, basmati riis, toorsalat', '2011-06-12 15:55:13', 'P&auml;evaprae hind 2,50 ( 39,12 )<br/>Uudis!!'),
('JAMJAM', '2011-06-07', 'Ahju sealiha kaetud meega, koorene sinepi kaste,keedukartul, praekapsas', '2011-06-12 15:55:13', 'P&auml;evaprae hind 2,50 ( 39,12 )<br/>Uudis!!'),
('KAPRIIS', '2011-06-10', 'Kirju pikkpoiss<br/>Juustupasta kreekapähklitega', '2011-06-12 15:55:05', ''),
('KAPRIIS', '2011-06-09', 'Kala-köögivilja pada<br/>Tomati-salaami pasta', '2011-06-12 15:55:05', ''),
('KAPRIIS', '2011-06-08', 'Prantsuspärane böfstrogonov<br/>Metsaseene pasta', '2011-06-12 15:55:05', ''),
('TARE', '2011-06-14', 'Kartuli-hakklihavorm,<br/>tillikaste,marineeritud kurk.<br/>V&auml;rskekapsabor&scaron;&scaron;.', '2011-06-14 11:15:20', 'KOMPLEKTL&Otilde;UNA<br/>praad, j&auml;&auml;vesi, soe kukkel<br/>3.30 &euro;/51.60 EEK<br/>P&Auml;EVASUPP<br/>1.60 &euro;/25.00 EEK'),
('SUUDLEVAD', '2011-06-14', 'Seene- ja sibulakattega sealiha, ürdikartulite, värske kapsa – porrulaugu salati ning koorese suitsujuustukastmega<br/>Kana - klimbisupp<br/>*Kergelt vürtsikas tomatine pastaroog veiselihaga<br/>', '2011-06-14 11:15:08', ''),
('MOKA', '2011-06-09', '„Nostalgia” grillkana sweetchilli majoneesiga EUR 4.41<br/>(ahjuküps citrus kana, praetud valge kapsa, juurviljade, prantsuse krõbe kartuli, sweet chilli ja majasalatiga)<br/>Majakook', '2011-06-12 15:55:13', ''),
('SPORDIBAAS', '2011-06-14', 'Barbecue sealiha keedukartulite ja värske salatiga 2,8€  Kana-aedvilja-nuudlisupp 1,4€', '2011-06-14 10:41:13', 'Päevapraad argipäeviti 12:00-15:00 Ettetellimine ja info: telefonil 58588575<br />\r\nVõimalus kaasa osta!'),
('NOIR', '2011-06-14', 'Koorene kanapasta brokoli ja parmesaniga 3,83 eur<br/>Roheline salat l&otilde;hefilee, meloni ja punase sibulaga 3,83 eur eur<br/>Rabarberikook j&auml;&auml;tise ja maasikatega 1,92 eur', '2011-06-14 11:15:15', ''),
('PANDA', '2011-06-14', '*Un-lau stiilis loomaliha / praetud riis köögiviljadega / salat - Praetud loomalihaviilud marineeritud kurgi, porgandi, bambusvõrsete, ananassi ja sibulaga meie enda spetsiaalses kastmes, praetud riis juurviljade ja munaga, värkse salat<br/>Tomati supp muna ja kanaga / aurutatud leib - Tomatisupp kanafilee ja munaga, aurutatud valge leib', '2011-06-14 11:15:21', 'Päevapraad hind - 2.80&#8364<br/>Päevasupp hind - 1.50&#8364'),
('KAPRIIS', '2011-06-07', 'Kana alõkk<br/>Veiseliha-pesto pasta', '2011-06-12 15:55:05', ''),
('KAPRIIS', '2011-06-06', 'Grillliha kukeseenekastmega<br/>Kana-maisi pasta', '2011-06-12 15:55:05', ''),
('PANDA', '2011-06-13', 'Magus-hapu kana / aurutatus riis / salat - Krõbedaks küpsetatud kanafileetükid köögiviljade ja ananassiga magus-hapus kastmes, aurutatud riis ja värkse salat.<br/>Kana-nuudli supp / küüslaugu leib - Selge supp kanaliha, nuudlite ja juurviljadega, praetud valge leib, maitsestatud küüslauguga', '2011-06-14 11:15:21', 'Päevapraad hind - 2.80&#8364<br/>Päevasupp hind - 1.50&#8364'),
('TREHV', '2011-06-16', 'Sealiha sinepises kastmes 3€</br>Kodune seljanka 1.5€</br>Jäätis šokolaadiga 1.5€', '2011-06-14 11:15:04', ''),
('TREHV', '2011-06-14', 'Vürtsikas sealiha 3€</br>Kuldne kalasupp 1.5€</br>Jäätis šokolaadiga 1.5€', '2011-06-14 11:15:04', ''),
('LOVISUDAME', '2011-06-06', 'Kanavarras 2.88<br/>Hakk - kotlet praemunaga 2.88', '2011-06-07 09:00:29', ''),
('TARE', '2011-06-15', 'Ahju&scaron;a&scaron;l&otilde;kk,ahjukartul,<br/>grillsalat.<br/>V&auml;rskekapsabor&scaron;&scaron;.', '2011-06-14 11:15:20', 'KOMPLEKTL&Otilde;UNA<br/>praad, j&auml;&auml;vesi, soe kukkel<br/>3.30 &euro;/51.60 EEK<br/>P&Auml;EVASUPP<br/>1.60 &euro;/25.00 EEK'),
('PARVIIZ', '2011-06-16', 'Õuntega pikitud sealiha</br>Kana-nuudlisupp', '2011-06-14 11:15:21', 'Praad suur 2.85 eur</br>Praad väike 2.25 eur</br>Supp suur 1.60 eur'),
('PARVIIZ', '2011-06-17', 'Täidetud paprika</br>Lihasupp läätsedega', '2011-06-14 11:15:21', 'Praad suur 2.85 eur</br>Praad väike 2.25 eur</br>Supp suur 1.60 eur'),
('ATLANTIS', '2011-06-16', 'Lestafilee terjaki-laimi marinaadis, aurutatud metsiku-riisi, v&auml;rske salat puuviljadega ja bernaisee kastmega', '2011-06-14 11:15:12', ''),
('VILDE', '2011-06-14', '&Scaron;nitsel kartulip&uuml;ree,<br/>creme de Paris<br/>kastme ja salatiga 3,30 EUR<br/>Tandoori kanakoib riisi, aedviljade ja jogurtikastmega 3,30 EUR<br/>V&uuml;rtsikas sealihasalat ananassi, juustu ja majoneesikastmega 3,30 EUR<br/>Lillkapsap&uuml;reesupp v&otilde;ileivaga 3,00 EUR<br/>Puuviljasalat vahukoorega 1,5 EUR', '2011-06-14 11:15:04', 'P&auml;evaprae juurde serveerime klaasi sidrunivett ja v&auml;rskelt k&uuml;psetatud Vilde leibasid maitsev&otilde;iga'),
('ATLANTIS', '0000-00-00', 'EUR/ 70.40 EEK/ komplekt', '2011-06-14 11:15:12', ''),
('ATLANTIS', '2011-06-13', 'Sekarbonaad rosmariini marinaadis pikitud suitsupeekoniga, juustuste ahjukartulite, marineeritud k&otilde;rvitsa-paprika salati ja koorekastmega', '2011-06-14 11:15:12', ''),
('ATLANTIS', '2011-06-15', 'Seafilee t&auml;idetud sinihallitusjuustuga, r&ouml;stitud kartulite ja k&ouml;&ouml;giviljade,tomati-kurgi salati,kirsi punaseveini kastmega', '2011-06-14 11:15:12', ''),
('MOKA', '2011-06-06', 'Suitsumaitseline forellifilee EUR 4.41<br/>(ahjuküps forellifilee paprikakastme, juurviljariisi ja majasalatiga)<br/>Majakook', '2011-06-12 15:55:13', ''),
('KINOKOHVIK', '2011-06-13', 'Pasta Carbonara', '2011-06-13 11:10:51', 'Lõunapakkumine 2,24 EUR, mehine lõuna 2,88 EUR'),
('ATLANTIS', '2011-06-14', 'Broilerifilee mangi-t&scaron;illi marinaadis, kartuli,broccoli,lillkapsa vormi, porgandi-&otilde;una salati ja m&auml;dar&otilde;ika k&uuml;lma kastmega', '2011-06-14 11:15:12', ''),
('TREHV', '2011-06-15', 'Heigifilee juustuga 3€</br>Külm keefirisupp 1.5€</br>Jäätis šokolaadiga 1.5€', '2011-06-14 11:15:04', ''),
('TARE', '2011-06-13', 'Grillitud kanakints,pannikartul,<br/>toorsalat.<br/>V&auml;rskekapsabor&scaron;&scaron;', '2011-06-14 11:15:20', 'KOMPLEKTL&Otilde;UNA<br/>praad, j&auml;&auml;vesi, soe kukkel<br/>3.30 &euro;/51.60 EEK<br/>P&Auml;EVASUPP<br/>1.60 &euro;/25.00 EEK'),
('PARVIIZ', '2011-06-15', 'Kanašnitsel</br>Värskekapsasupp', '2011-06-14 11:15:21', 'Praad suur 2.85 eur</br>Praad väike 2.25 eur</br>Supp suur 1.60 eur'),
('PARVIIZ', '2011-06-14', 'Kodused kotletid</br>Hernesupp', '2011-06-14 11:15:21', 'Praad suur 2.85 eur</br>Praad väike 2.25 eur</br>Supp suur 1.60 eur'),
('PARVIIZ', '2011-06-13', 'Gruusia kanapraad</br>Guljašš supp', '2011-06-14 11:15:21', 'Praad suur 2.85 eur</br>Praad väike 2.25 eur</br>Supp suur 1.60 eur'),
('SPORDIBAAS', '2011-06-15', 'Mõnusalt krõbe kanafilee värvilise riisiga 2,8€ Hernesupp 1,4€', '2011-06-14 10:41:13', 'Päevapraad argipäeviti 12:00-15:00 Ettetellimine ja info: telefonil 58588575<br />\r\nVõimalus kaasa osta!');

-- --------------------------------------------------------

--
-- Struktuur tabelile `diners`
--

CREATE TABLE IF NOT EXISTS `diners` (
  `code` varchar(10) character set utf8 collate utf8_unicode_ci NOT NULL,
  `name` varchar(30) collate utf8_estonian_ci NOT NULL,
  `url` varchar(50) character set utf8 collate utf8_unicode_ci default NULL,
  `enabled` tinyint(1) NOT NULL default '1',
  `updateenabled` tinyint(1) NOT NULL default '1',
  `manualupdateenabled` tinyint(1) NOT NULL default '0',
  `password` varchar(32) character set utf8 collate utf8_unicode_ci default NULL,
  `defaultinfo` varchar(1000) character set utf8 collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci;

--
-- Tabeli andmete salvestamine `diners`
--

INSERT INTO `diners` (`code`, `name`, `url`, `enabled`, `updateenabled`, `manualupdateenabled`, `password`, `defaultinfo`) VALUES
('PATTAYA', 'Tirol Pub (Pattaya)', 'www.pattaya.ee', 0, 0, 0, NULL, NULL),
('AURA', 'Aura Kohvik', 'aurakohvik.ee', 1, 1, 0, '', ''),
('TARTU', 'Tartu Kohvik', 'www.tartukohvik.ee', 1, 1, 0, '', ''),
('TREHV', 'Trehv', 'www.trehv.ee', 1, 1, 0, '', ''),
('VILDE', 'Vilde', 'vilde.ee', 1, 1, 0, '', ''),
('KROOKS', 'Krooksu Pubi', 'www.krooks.ee', 1, 1, 0, '', ''),
('PLACE', 'Place Beer Colors', 'www.bcplace.ee', 0, 0, 0, NULL, NULL),
('PANG', 'Tsink Plekk Pang', 'www.pang.ee', 1, 1, 0, '', ''),
('KAPRIIS', 'Kapriis', 'www.kapriis.ee', 1, 1, 0, '', ''),
('GLAM', 'Gläm', 'www.glam.ee', 0, 0, 0, NULL, NULL),
('SUUDLEVAD', 'Suudlevad Tudengid', 'www.suudlevadtudengid.ee', 1, 1, 0, '', ''),
('PYSS', 'Püssirohukelder', 'www.pyss.ee', 1, 1, 0, '', ''),
('UT', 'Ülikooli Kohvik', 'www.kohvik.ut.ee', 0, 0, 0, NULL, NULL),
('ENTRI', 'Entri Restoran', 'www.entri.ee', 1, 1, 0, '', ''),
('TRUFFE', 'Truffe', 'www.truffe.ee', 1, 1, 0, '', ''),
('BARCLAY', 'Barclay Restoran', 'www.restoranbarclay.ee', 0, 0, 0, NULL, NULL),
('ATLANTIS', 'Atlantise Restoran', 'www.atlantis.ee', 1, 1, 0, '', ''),
('KOTKA', 'Kotka Kelder', 'www.kotkakelder.ee', 1, 1, 0, '', ''),
('NOIR', 'Noir', 'www.cafenoir.ee', 1, 1, 0, '', ''),
('AMPS', 'Amps ja Naps', 'ampsnaps.infopluss.ee', 0, 0, 0, NULL, NULL),
('ASIANCHEF', 'Asian Chef', 'www.asianchef.ee', 1, 1, 0, '', ''),
('MOKA', 'Moka Kohvik', 'moka.ee', 1, 1, 0, '', ''),
('JAMJAM', 'JamJam', 'jamjam.ee', 1, 1, 0, '', ''),
('TARE', 'Õlletare', 'olletare.ee', 1, 1, 0, '', ''),
('LOVISUDAME', 'Lõvisüdame Kohvik', 'xn--lvisdame-e4a7e.eu', 1, 1, 0, '', ''),
('SPORDIBAAS', 'Spordibaas', 'spordibaas.ee', 1, 0, 1, '82b461792b897edb8f96fa7e0434feba', 'Päevapraad argipäeviti 12:00-15:00 Ettetellimine ja info: telefonil 58588575<br />\r\nVõimalus kaasa osta!'),
('COOKBOOK', 'Cookbook', NULL, 0, 0, 1, '6e70e073b843294ebae53853eae23827', 'sdf<br />\r\nsdfsdffd d'),
('REBASE', 'Rebase Söögituba', 'rebase.ee', 0, 0, 0, '8452bba9ac58db4df340c5836edf5ce3', 'NÜÜDSEST PAKUME KA HOMMIKUBUFFEED 8.30-10.00. VALIKUS PUDRUD, SAIAKESED, TOORSALAT, VÄRKSE KOHVI JA TEE!<br />\r\nSOOVI KORRAL TOOME SOOJA LÕUNE OTSE TEIE KONTORISSE!'),
('KINOKOHVIK', 'Kinohvik', 'www.cinamon.ee/?id=188', 1, 0, 1, 'b81178b63cfd7402e255a7daffb28721', 'Lõunapakkumine 2,24 EUR, mehine lõuna 2,88 EUR'),
('PARVIIZ', 'Parviiz', 'parviiz.ee', 1, 1, 0, '', ''),
('VOLGA', 'Volga Restoran', 'restaurantvolga.ee', 1, 0, 0, '', ''),
('BIGBEN', 'Big Ben', 'www.bigbenpub.ee', 0, 0, 1, '0569030d22a3d118daa84c89bf6694a4', ''),
('KAHKUKAS', 'Kähkukas', 'kahkukas.ee', 1, 0, 0, '', ''),
('YAKUZA', 'Yakuza Sushi Bar', 'yakuzasushi.ee', 1, 0, 1, 'b5129b93a4c41aa6b2d4774edaf32471', 'Lisaks päevapakkumistele, tööpäeviti 12.00-16.00 pakutakse Bento lõunasööki (supp, praad või sushi valik, värske salat, jaapani tee).'),
('RANDURI', 'Ränduri Pubi', NULL, 1, 0, 1, '5eb6503f0f49833bad93dcc4a626f713', 'Päevaprae hind 3.20 EUR<br />\r\nPäevapraadi pakume alates kell 12.00<br />\r\npäevasupp 2 EUR<br />\r\nmagustoit 1EUR<br />\r\nBuffee avatud 12.00-14.30'),
('PANDA', 'Panda Restoran', 'pandarestoran.ee', 1, 1, 0, '', '');

-- --------------------------------------------------------

--
-- Struktuur tabelile `filterkeywords`
--

CREATE TABLE IF NOT EXISTS `filterkeywords` (
  `CODE` varchar(20) collate utf8_estonian_ci NOT NULL,
  `KEYWORD` varchar(20) collate utf8_estonian_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci;

--
-- Tabeli andmete salvestamine `filterkeywords`
--

INSERT INTO `filterkeywords` (`CODE`, `KEYWORD`) VALUES
('KALA', 'heik'),
('KALA', 'heigi'),
('KALA', 'forel'),
('KALA', 'mintai'),
('KALA', 'lõhe'),
('KALA', 'l&otilde;he'),
('KALA', 'pangasius'),
('KALA', 'heeringa'),
('KALA', 'kilu'),
('KALA', 'räim'),
('KALA', 'kala'),
('KALA', 'tursa'),
('KALA', 'tursk'),
('KALA', 'lest'),
('KALA', 'r&auml;im'),
('LIND', 'kana'),
('LIND', 'kalkun'),
('KALA', 'latikas'),
('KALA', 'latika'),
('LIND', 'broiler'),
('SEEN', 'seene'),
('SEEN', 'shampinjon'),
('SEEN', 'šampinjon'),
('SEEN', '&scaron;ampinjon'),
('SEEN', '&scaron;hampinjon'),
('KALA', 'ahven'),
('LIND', 'pardi'),
('LIND', 'part'),
('SEEN', 'seente'),
('KALA', 'tilaapia'),
('SEEN', 'puravik'),
('KALA', 'pangassius'),
('KALA', 'keta'),
('LIND', 'linnu'),
('KALA', 'merihunt'),
('KALA', 'merihundi');

-- --------------------------------------------------------

--
-- Struktuur tabelile `filters`
--

CREATE TABLE IF NOT EXISTS `filters` (
  `CODE` varchar(20) collate utf8_estonian_ci NOT NULL,
  `NAME` varchar(20) collate utf8_estonian_ci NOT NULL,
  `COLOR` varchar(7) collate utf8_estonian_ci NOT NULL,
  `LIGHTCOLOR` varchar(7) collate utf8_estonian_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci;

--
-- Tabeli andmete salvestamine `filters`
--

INSERT INTO `filters` (`CODE`, `NAME`, `COLOR`, `LIGHTCOLOR`) VALUES
('KALA', 'Kalast', 'red', '#66FF00'),
('LIND', 'Linnust', 'green', '#00FF00'),
('SEEN', 'Seentest', 'blue', '#002266');
