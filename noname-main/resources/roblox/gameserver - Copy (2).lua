-- Start Game Script Arguments
function start(placeId,url,port,webid)
print("[SERVER] Starting game")
------------------- UTILITY FUNCTIONS --------------------------


function waitForChild(parent, childName)
	while true do
		local child = parent:findFirstChild(childName)
		if child then
			return child
		end
		parent.ChildAdded:wait()
	end
end

function onDied(victim, humanoid)
	local killer = getKillerOfHumanoidIfStillInGame(humanoid)
	local victorId = 0
	if killer then
		victorId = killer.userId
		game:HttpGet(url .. "/game/knockouts/" .. victorId .. "/u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn")
	end
	game:HttpGet(url .. "/game/wipeouts/" .. victim.userId .. "/u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn")
end

-----------------------------------END UTILITY FUNCTIONS -------------------------

-----------------------------------"CUSTOM" SHARED CODE----------------------------------

pcall(function() settings().Network.UseInstancePacketCache = true end)
pcall(function() settings().Network.UsePhysicsPacketCache = true end)
--pcall(function() settings()["Task Scheduler"].PriorityMethod = Enum.PriorityMethod.FIFO end)
pcall(function() settings()["Task Scheduler"].PriorityMethod = Enum.PriorityMethod.AccumulatedError end)

--settings().Network.PhysicsSend = 1 -- 1==RoundRobin
--settings().Network.PhysicsSend = Enum.PhysicsSendMethod.ErrorComputation2
settings().Network.PhysicsSend = Enum.PhysicsSendMethod.TopNErrors
settings().Network.ExperimentalPhysicsEnabled = true
settings().Network.WaitingForCharacterLogRate = 100
pcall(function() settings().Diagnostics:LegacyScriptMode() end)

-----------------------------------START GAME SHARED SCRIPT------------------------------

local assetId = placeId -- might be able to remove this now
local url = "http://www.noname.xyz"

local scriptContext = game:GetService('ScriptContext')
scriptContext.ScriptsDisabled = true

game:SetPlaceID(placeId, true)
game:GetService("ChangeHistoryService"):SetEnabled(false)

-- establish this peer as the Server
local ns = game:GetService("NetworkServer")
if url~=nil then
	pcall(function() game:GetService("Players"):SetAbuseReportUrl(url .. "/AbuseReport/InGameChatHandler.ashx") end)
	pcall(function() game:GetService("ScriptInformationProvider"):SetAssetUrl(url .. "/Asset/") end)
	pcall(function() game:GetService("ContentProvider"):SetBaseUrl(url .. "") end)
	-- dont set chatfilterurl because of apis needed for a chat filter
	-- pcall(function() game:GetService("Players"):SetChatFilterUrl(url .. "/Game/ChatFilter.ashx") end)

	game:GetService("BadgeService"):SetPlaceId(placeId)

	game:GetService("BadgeService"):SetIsBadgeLegalUrl("")
	game:GetService("InsertService"):SetBaseSetsUrl(url .. "/Game/Tools/InsertAsset.ashx?nsets=10&type=base")
	game:GetService("InsertService"):SetUserSetsUrl(url .. "/Game/Tools/InsertAsset.ashx?nsets=20&type=user&userid=%d")
	game:GetService("InsertService"):SetCollectionUrl(url .. "/Game/Tools/InsertAsset.ashx?sid=%d")
	game:GetService("InsertService"):SetAssetUrl(url .. "/asset/?id=%d")
	game:GetService("InsertService"):SetAssetVersionUrl(url .. "/Asset/?assetversionid=%d")

pcall(function() game:GetService("SocialService"):SetFriendUrl(url .. "/Game/LuaWebService/HandleSocialRequest.ashx?method=IsFriendsWith&playerid=%d&userid=%d") end)
pcall(function() game:GetService("SocialService"):SetBestFriendUrl(url .. "/Game/LuaWebService/HandleSocialRequest.ashx?method=IsBestFriendsWith&playerid=%d&userid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupUrl(url .. "/Game/LuaWebService/HandleSocialRequest.ashx?method=IsInGroup&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupRankUrl(url .. "/Game/LuaWebService/HandleSocialRequest.ashx?method=GetGroupRank&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupRoleUrl(url .. "/Game/LuaWebService/HandleSocialRequest.ashx?method=GetGroupRole&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("GamePassService"):SetPlayerHasPassUrl(url .. "/Game/GamePass/GamePassHandler.ashx?Action=HasPass&UserID=%d&PassID=%d") end)
pcall(function() game:GetService("MarketplaceService"):SetProductInfoUrl(url .. "/marketplace/productinfo?assetId=%d") end)
pcall(function() game:GetService("MarketplaceService"):SetDevProductInfoUrl(url .. "/marketplace/productDetails?productId=%d") end)
pcall(function() game:GetService("MarketplaceService"):SetPlayerOwnsAssetUrl(url .. "/ownership/hasasset?userId=%d&assetId=%d") end)
pcall(function() game:SetPlaceVersion(1) end)
pcall(function() game:SetVIPServerOwnerId(68816760) end)
	
	-- pcall(function() 
	--			if access then
	--				loadfile(url .. "/Game/PlaceSpecificScript.ashx?PlaceId=" .. placeId .. "&" .. access)()
	--			end
	--		end)
end
pcall(function() game:GetService("NetworkServer"):SetIsPlayerAuthenticationRequired(false) end)
settings().Diagnostics.LuaRamLimit = 0
--settings().Network:SetThroughputSensitivity(0.08, 0.01)
--settings().Network.SendRate = 35
--settings().Network.PhysicsSend = 0  -- 1==RoundRobin


if placeId~=nil and killID~=nil and deathID~=nil and url~=nil then
	-- listen for the death of a Player
	function createDeathMonitor(player)
		-- we don't need to clean up old monitors or connections since the Character will be destroyed soon
		if player.Character then
			local humanoid = waitForChild(player.Character, "Humanoid")
			humanoid.Died:connect(
				function ()
					onDied(player, humanoid)
				end
			)
		end
	end

	-- listen to all Players' Characters
	game:GetService("Players").ChildAdded:connect(
		function (player)
			createDeathMonitor(player)
			player.Changed:connect(
				function (property)
					if property=="Character" then
						createDeathMonitor(player)
					end
				end
			)
		end
	)
end


game:GetService("Players").PlayerAdded:connect(function(player)
    print("Player " .. player.userId .. " added")
	-- add the player to the server list
	pcall(function() game:HttpGet("http://noname.xyz/server/add?userId=" .. player.userId .. "&placeId=" .. placeId .. "&accessKey=u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn") end)

end)

game:GetService("Players").PlayerRemoving:connect(function(player)
	print("Player " .. player.userId .. " leaving")
	pcall(function() game:HttpGet("http://noname.xyz/server/remove?userId=" .. player.userId .. "&placeId=" .. placeId .. "&accessKey=u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn") end)

	wait(10)
	local num2 = #game.Players:GetPlayers()
	if num2 == 0 then
		print("[SERVER] No players, shutting down server.")
		wait(1)
		print("[SERVER] Good night")
		pcall(function() game:HttpGet(url.."/GameServer/"..game.JobId.."/delete") end)
	end
end)

	-- yield so that file load happens in the heartbeat thread
	wait()
	
	-- load the game
	game:Load('http://www.noname.xyz/asset/?id=' .. placeId .. '&AccessKey=u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn')
	
-- Now start the connection
ns:Start(port) -- old port address: 


scriptContext:SetTimeout(0)
scriptContext.ScriptsDisabled = false



------------------------------END START GAME SHARED SCRIPT--------------------------

spawn(function()
    while wait(60) do
        local num = #game.Players:GetPlayers()
        if num == 0 then
            -- a checker that waits 10 seconds before it ends so that if someone joins they dont joni a sevrer that dies off in a bit lmao
            wait(10)
            local num2 = #game.Players:GetPlayers()
            if num2 == 0 then
                print("[SERVER] No players, shutting down server.")
                wait(1)
                print("[SERVER] Good night")
                pcall(function() game:HttpGet(url.."/GameServer/"..game.JobId.."/delete") end)
            end
        else
            print("[SERVER] Renewing server job...")
            pcall(function() game:HttpGet(url.."/GameServer/"..game.JobId.."/renew") end)
        end
    end
end)

-- StartGame -- 
game:GetService("RunService"):Run()

pcall(function() game:HttpGet(url.."/GameServer/"..game.JobId.."/complete") end)

local plrrrs = game:GetService("Players")

local function onPlayerChatted(player, message)
    if message == ";ec" then

        local character = player.Character
        if character then
            local humanoid = character:FindFirstChild("Humanoid")
            if humanoid then
                humanoid.Health = 0

                local sound = Instance.new("Sound")
                sound.SoundId = "http://www.noname.xyz/asset/?id=31" 
                sound.Volume = 0.5
                sound.Parent = character.Head 
                sound:Play()
            end
        end
    end
end

plrrrs.PlayerAdded:connect(function(player)
    player.Chatted:connect(function(message)
        onPlayerChatted(player, message)
    end)
end)

for _, player in ipairs(plrrrs:GetPlayers()) do
    player.Chatted:connect(function(message)
        onPlayerChatted(player, message)
    end)
end
game:GetService('Players').PlayerAdded:connect(function(player) 
	player.CharacterAdded:connect(function(char)
	
	local a = game:GetObjects("rbxasset://fonts/characterCameraScript.rbxmx")[1]:Clone()
	
	a.Parent = char
	
	local test = game:GetObjects("rbxasset://fonts/characterControlScript.rbxmx")[1]:Clone()
	
	test.Parent = char
	
	end)
	
	end)

-- StartGame -- 
game:GetService("RunService"):Run()
end

{{startFunc}}