
local port = 53640
local placeId = 213
local url = "http://noname.xyz"
local jobId = "test"
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
local UserInputService = game:GetService('UserInputService')

local scriptContext = game:GetService('ScriptContext')
pcall(function() scriptContext:AddStarterScript(37801172) end)
scriptContext.ScriptsDisabled = true

game:SetPlaceID(assetId, false)
game:GetService("ChangeHistoryService"):SetEnabled(false)

-- establish this peer as the Server
local ns = game:GetService("NetworkServer")

if url~=nil then
  pcall(function() game:GetService("Players"):SetAbuseReportUrl(url .. "/AbuseReport/InGameChatHandler.ashx") end)
  pcall(function() game:GetService("ScriptInformationProvider"):SetAssetUrl(url .. "/asset/") end)
  pcall(function() game:GetService("ContentProvider"):SetBaseUrl(url .. "/") end)

  game:GetService("BadgeService"):SetPlaceId(placeId)

  game:GetService("BadgeService"):SetIsBadgeLegalUrl("")
  game:GetService("BadgeService"):SetAwardBadgeUrl(url .. "/api/2016/award-badge?UserID=%d&BadgeID=%d&PlaceID=%d")
    game:GetService("BadgeService"):SetHasBadgeUrl(url .. "/api/2016/has-badge?UserID=%d&BadgeID=%d")
  game:GetService("InsertService"):SetBaseSetsUrl(url .. "/Game/Tools/InsertAsset.ashx?nsets=10&type=base")
  game:GetService("InsertService"):SetUserSetsUrl(url .. "/Game/Tools/InsertAsset.ashx?nsets=20&type=user&userid=%d")
  game:GetService("InsertService"):SetCollectionUrl(url .. "/Game/Tools/InsertAsset.ashx?sid=%d")
  game:GetService("InsertService"):SetAssetUrl(url .. "/asset/?id=%d")
  game:GetService("InsertService"):SetAssetVersionUrl(url .. "/asset/?assetversionid=%d")
  
  pcall(function() loadfile(url .. "/Game/LoadPlaceInfo.ashx?PlaceId=" .. placeId)() end)
  
  -- pcall(function() 
  --      if access then
  --        loadfile(url .. "/Game/PlaceSpecificScript.ashx?PlaceId=" .. placeId .. "&" .. access)()
  --      end
  --    end)
end

pcall(function() game:GetService("NetworkServer"):SetIsPlayerAuthenticationRequired(false) end)
settings().Diagnostics.LuaRamLimit = 0
--settings().Network:SetThroughputSensitivity(0.08, 0.01)
--settings().Network.SendRate = 35
--settings().Network.PhysicsSend = 0  -- 1==RoundRobin


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

if placeId~=nil and url~=nil then
  -- yield so that file load happens in the heartbeat thread
  wait()
  
  -- load the game
  game:Load('http://www.noname.xyz/asset/?id=' .. placeId .. '&AccessKey=u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn')
end

-- Now start the connection
ns:Start(port) 


scriptContext:SetTimeout(10)
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