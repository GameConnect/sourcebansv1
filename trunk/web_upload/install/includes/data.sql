INSERT INTO `{prefix}_mods` (`mid`, `name`, `icon`, `modfolder`) VALUES
(1, 'Web', 'web.png', 'NULL'),
(2, 'Half-Life 2 Deathmatch', 'hl2dm.png', 'hl2mp'),
(3, 'Counter-Strike: Source', 'csource.png', 'cstrike'),
(4, 'Day of Defeat: Source', 'dods.png', 'dod'),
(5, 'Insurgency: Source', 'ins.gif', 'insurgency'),
(6, 'Dystopia', 'dys.gif', 'dystopia_v1'),
(7, 'Hidden: Source', 'hidden.png', 'hidden'),
(8, 'Half-Life 2 Capture the Flag', 'hl2ctf.png', 'hl2ctf'),
(9, 'Pirates Vikings and Knights II', 'pvkii.gif', 'pvkii'),
(10, 'Perfect Dark: Source', 'pdark.gif', 'pdark'),
(11, 'The Ship', 'ship.gif', 'ship'),
(12, 'Fortress Forever', 'hl2-fortressforever.gif', 'FortressForever'),
(13, 'Team Fortress 2', 'tf2.gif', 'tf'),
(14, 'Zombie Panic', 'zps.gif', 'zps'),
(15, "Garry's Mod", 'gmod.png', 'garrysmod'),
(16, "Left 4 Dead", 'l4d.png', 'left4dead'),
(17, "Left 4 Dead 2", 'l4d2.png', 'left4dead2');

UPDATE `{prefix}_mods` SET `mid` = '0' WHERE `name` = 'Web';

INSERT INTO `{prefix}_settings` (`setting`, `value`) VALUES
('dash.intro.text', '<img src="images/logo-large.jpg" border="0" width="800" height="126" /><h3>Your new SourceBans install</h3><p>SourceBans successfully installed!</p>'),
('dash.intro.title', 'Your SourceBans install'),
('dash.lognopopup', '0'),
('banlist.bansperpage', '30'),
('banlist.hideadminname', '0'),
('banlist.nocountryfetch', '0'),
('banlist.hideplayerips', '0'),
('bans.customreasons', ''),
('config.password.minlength', '4'),
('config.debug', '0 '),
('template.logo', 'logos/sb-large.png'),
('template.title', 'SourceBans'),
('config.enableprotest', '1'),
('config.enablesubmit', '1'),
('config.exportpublic', '0'),
('config.enablekickit', '1'),
('config.dateformat', ''),
('config.theme', 'default'),
('config.defaultpage', '0'),
('config.timezone', '0'),
('config.summertime', '0'),
('config.enablegroupbanning', '0'),
('config.enablefriendsbanning', '0'),
('config.enableadminrehashing', '1'),
('protest.emailonlyinvolved', '0'),
('config.version', '351');


INSERT INTO `{prefix}_admins` (
`aid` ,	`user` , `authid` ,	`password` , `gid` , `email` ,	`validate` , `extraflags`, `immunity`)
VALUES (
NULL , 'CONSOLE', 'STEAM_ID_SERVER', '', '0', '', '', '0', 0);

UPDATE `{prefix}_admins` SET `aid` = '0' WHERE `authid` = 'STEAM_ID_SERVER';
