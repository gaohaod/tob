local port = {port}
local placeId = {placeId}
local url = "http://noname.xyz"
local jobId = "{jobId}"
local gearsEnabled = {GE}
game:Load('http://www.noname.xyz/asset/?id=' .. placeId .. '&AccessKey=u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn')
print("Loading map")

local scriptContext = game:GetService('ScriptContext')
pcall(function() scriptContext:AddStarterScript(libraryRegistrationScriptAssetID) end)
scriptContext.ScriptsDisabled = false
game:SetPlaceID(placeId, false)
game:GetService("ChangeHistoryService"):SetEnabled(false)
pcall(function() settings().Network.UseInstancePacketCache = true end)
pcall(function() settings().Network.UsePhysicsPacketCache = true end)
--pcall(function() settings()["Task Scheduler"].PriorityMethod = Enum.PriorityMethod.FIFO end)
pcall(function() settings()["Task Scheduler"].PriorityMethod = Enum.PriorityMethod.AccumulatedError end)
--settings().Network.PhysicsSend = 1 -- 1==RoundRobin
settings().Network.PhysicsSend = Enum.PhysicsSendMethod.ErrorComputation2
settings().Network.ExperimentalPhysicsEnabled = true
settings().Network.WaitingForCharacterLogRate = 100
pcall(function() settings().Diagnostics:LegacyScriptMode() end)
game:GetService("RunService"):Run()
game:GetService("NetworkServer"):Start(port)
pcall(function() game:GetService("Players"):SetChatStyle(Enum.ChatStyle.Both) end)

game:GetService("Players").PlayerAdded:connect(function(player)
  print("Player " .. player.userId .. " added")

-- add the player to the server list
pcall(function() game:HttpGet("http://noname.xyz/server/add?userId=" .. player.userId .. "&placeId=" .. placeId .. "&accessKey=u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn") end)
end)

game:GetService("Players").PlayerRemoving:connect(function(player)
print("Player " .. player.userId .. " leaving")
pcall(function() game:HttpGet("http://noname.xyz/server/remove?userId=" .. player.userId .. "&placeId=" .. placeId .. "&accessKey=u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn") end)
end)

  local tag = humanoid:findFirstChild("creator")

  if tag then
    local killer = tag.Value
    if killer.Parent then 
      return killer
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


--Author: Atomic
--Date: 9/22/2020
--Due to a Roblox update, asset links (http://roblox.com/asset?id=) don't work because Roblox is using a different link for assets now. 2020 Roblox can automatically change the link to the new asset link, but not old clients.  
--This script can look through a game to change asset links to what Roblox uses now. (https://assetdelivery.roblox.com/v1/asset?id=)
--It does not change scripts! Only properties of objects that ask for an asset link, like decals, sounds, and tools.

--Make sure to backup your place in case something goes wrong, or you need to revert changes!

local assetPropertyNames = {"Texture", "TextureId", "SoundId", "MeshId", "SkyboxUp", "SkyboxLf", "SkyboxBk", "SkyboxRt", "SkyboxFt", "SkyboxDn", "PantsTemplate", "ShirtTemplate", "Graphic", "Image", "LinkedSource", "AnimationId"}
local variations = {"http://www%.roblox%.com/asset/%?id=", "http://www%.roblox%.com/asset%?id=", "http://%.roblox%.com/asset/%?id=", "http://%.roblox%.com/asset%?id="}

function GetDescendants(o)
    local allObjects = {}
    function FindChildren(Object)
       for _,v in pairs(Object:GetChildren()) do
            table.insert(allObjects,v)
            FindChildren(v)
        end
    end
    FindChildren(o)
    return allObjects
end

local replacedProperties = 0--Amount of properties changed

for i, v in pairs(GetDescendants(game)) do
  for _, property in pairs(assetPropertyNames) do
    pcall(function()
      if v[property] and not v:FindFirstChild(property) then --Check for property, make sure we're not getting a child instead of a property
        assetText = string.lower(v[property])
        for _, variation in pairs(variations) do
          v[property], matches = string.gsub(assetText, variation, "http://noname.xyz/asset?id=")
          if matches > 0 then
            replacedProperties = replacedProperties + 1
            print("Replaced " .. property .. " asset link for " .. v.Name)
            break
          end
        end
      end
    end)
  end
end

print("DONE! Replaced " .. replacedProperties .. " properties")

if placeId~=nil and killID~=nil and deathID~=nil and url~=nil then
  -- listen for the death of a Player
  function createDeathMonitor(player)
    -- we don\'t need to clean up old monitors or connections since the Character will be destroyed soon
    if player.Character then
      local humanoid = waitForChild(player.Character, "Humanoid")
      humanoid.Died:connect(
        function ()
          onDied(player, humanoid)
        end
      )
    end
  end

  -- listen to all Players\' Characters
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