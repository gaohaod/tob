print("[THUMBNAIL] Render Avatar")
local t = game:GetService("ThumbnailGenerator")
local player = game.Players:CreateLocalPlayer(0)
game:GetService("ContentProvider"):SetBaseUrl("http://noname.xyz/")
game:GetService("ScriptContext").ScriptsDisabled = true


player.CharacterAppearance = "http://www.noname.xyz/char/{{userid}}?rcc=true&t={{time}}"
player:LoadCharacter(false)

local gear = player.Backpack:GetChildren()[1] 
    if gear then 
        gear.Parent = player.Character 
        player.Character.Torso['Right Shoulder'].CurrentAngle = math.rad(90) 
    end

return game:GetService("ThumbnailGenerator"):Click("PNG", 420, 420, true)